# Discord Bot for Live Helper Chat

This repository helps you set up a Discord bot that can answer questions from documentation, similar to the one in the Live Helper Chat Discord channel.

## Demo

* Join our [Discord server](https://discord.gg/YsZXQVh)
* The help channel is available at [https://discord.com/channels/711499430154731520/1300394139895988244](https://discord.com/channels/711499430154731520/1300394139895988244)

## Integrating with Live Helper Chat
 
### For receiving messages

* Import `lhc/incoming-webhook.json` file in `Home > System configuration > Incoming webhooks`.
* Change the `Identifier` field and update `Attributes > bot_token` (you can see this by clicking "Show integration information").
* Choose a department for the bot (it's recommended to create a new department specifically for Discord integration).
* Copy the `URL to put in third party Rest API service` for later use.

### For Sending Messages

* Import `lhc/restp-api.json` file in `Home > System configuration > Rest API Calls`. 
* Import `lhc/bot.json` file in `Bots > Import`. During import choose in previous step imported `Rest API`
* Create webhook as per screenshot. For those events webhooks should be created.
  * `chat.before_auto_responder_msg_saved` 
  * `chat.web_add_msg_admin` 
  * `chat.workflow.canned_message_before_save` 
  * `chat.desktop_client_admin_msg`

![See image](https://raw.githubusercontent.com/LiveHelperChat/discord-bot/master/lhc/incoming-webhook.png)

## Running NodeJS server

* Clone this repository
* Copy `discord/discord-server/.env.default` to `discord/discord-server/.env`
* Modify variables in the `.env` file. You'll need to paste the Webhook URL you copied earlier.
* Build the server with `docker-compose up --build` to test. Once everything is working, run `docker-compose up -d` to run it as a service.

# How to create a bot in Discord?

Creating a Discord bot is not within the scope of this repository, but here are some resources to help you:

* Watch this tutorial: [How to Create a Discord Bot](https://www.youtube.com/watch?v=Oy5HGvrxM4o)
* Visit the [Discord Developer Portal](https://discord.com/developers/applications) to create your application and bot
* Generate an invitation link with appropriate permissions to add the bot to your server
