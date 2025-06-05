# Discord Bot

To have discord bot as Live Helper Chat Discord channel has which can answer questions from documentation you will need. 

* https://discord.gg/YsZXQVh Discord server
* Channel where people ask for bot help - https://discord.com/channels/711499430154731520/1300394139895988244

## Integrating in LHC
 
### For receiving messages

* Import `lhc/incoming-webhook.json` file in `Home > System configuration > Incoming webhooks`. Change `Identifier` and (Click - Show integration information) `Attributes > bot_token`. Choose department, I would suggest creating a new department just for Discord.
* Copy somewhere `URL to put in third party Rest API service`

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

* Close this repository
* `discord/discord-server/.env.default` copy `discord/discord-server/.env`
* Modify variables in `.env`. You will need that one `Webhook URL`
* Build server `docker-compose up --build` once you test and all works `docker-compose up -d` to run as service.

# How to create a bot in Discord?

That's not a scope of this repository and you should do that on your own. Create a bot and add it to your server.

 * Just watch - https://www.youtube.com/watch?v=Oy5HGvrxM4o
 * https://discord.com/developers/applications