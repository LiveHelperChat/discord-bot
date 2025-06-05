// index.js
const { Client, GatewayIntentBits, Events, REST, Routes, SlashCommandBuilder } = require('discord.js');
require('dotenv').config();

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent
    ]
});

// Define slash commands
const commands = [
    new SlashCommandBuilder()
        .setName('ping')
        .setDescription('Replies with Pong!')
];

// Register slash commands
async function registerCommands() {
    if (!process.env.DISCORD_CLIENT) {
        console.error('DISCORD_CLIENT environment variable is not set. Skipping slash command registration.');
        return;
    }
    
    const rest = new REST({ version: '10' }).setToken(process.env.DISCORD_TOKEN);
    
    try {
        console.log('Started refreshing application (/) commands.');
        
        await rest.put(
            Routes.applicationCommands(process.env.DISCORD_CLIENT),
            { body: commands }
        );
        
        console.log('Successfully reloaded application (/) commands.');
    } catch (error) {
        console.error(error);
    }
}

client.once(Events.ClientReady, async () => {
    console.log(`Logged in as ${client.user.tag}`);
    await registerCommands();
});

// Handle slash commands
client.on(Events.InteractionCreate, async interaction => {
    if (!interaction.isChatInputCommand()) return;

    if (interaction.commandName === 'ping') {
        await interaction.reply('Pong!');
    }
});

client.on(Events.MessageCreate, message => {
    
    // Uncomment for debugging purposes
    // console.log(JSON.stringify(message, null, 2));
    
    if (message.mentions.has(client.user) && !message.author.bot) {

        if (message.author.username === process.env.DISCORD_OWNER_USERNAME && message.channelId !== process.env.DISCORD_CHANNEL_ANSWER) {
            message.reply(process.env.DISCORD_OWNER_IGNORE_MSG);
            return;
        }

        if (message.content === 'ping') {
            message.reply("pong");
        } else {
            const messageData = {
                ...message,
                cleanContentPlain: message.cleanContent.replace(/@\S+/g, '').trim()
            };
            
            fetch( process.env.LHC_WEBHOOK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(messageData)
            }).catch(error => console.error('Error sending webhook:', error));

            // message.reply(`You mentioned me? ${message.content}`);
        }      
    } 
});

client.login(process.env.DISCORD_TOKEN);