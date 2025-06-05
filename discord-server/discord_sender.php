<?php
/**
 * Discord Reply Message Sender
 * Sends reply messages to Discord using CURL with discord.js-based parameters
 */

class DiscordReplySender {
    private $botToken;
    private $baseUrl = 'https://discord.com/api/v10';
    
    public function __construct($botToken) {
        $this->botToken = $botToken;
    }
    
    /**
     * Send a reply message to Discord
     * 
     * @param array $params Parameters based on discord.js library structure
     * @return array Response from Discord API
     */
    public function sendReply($params) {
        // Validate required parameters
        if (!isset($params['channel_id']) || !isset($params['message_reference']['message_id'])) {
            throw new Exception('Missing required parameters: channel_id and message_reference.message_id');
        }
        
        $channelId = $params['channel_id'];
        $url = $this->baseUrl . "/channels/{$channelId}/messages";
        
        // Build the payload based on discord.js structure
        $payload = [
            'content' => $params['content'] ?? '',
            /*'message_reference' => [
                'message_id' => $params['message_reference']['message_id'],
                'channel_id' => $params['message_reference']['channel_id'] ?? $channelId,
                'guild_id' => $params['message_reference']['guild_id'] ?? null,
                'fail_if_not_exists' => $params['message_reference']['fail_if_not_exists'] ?? false
            ]*/
        ];
        
        // Add optional parameters
        if (isset($params['embeds'])) {
            $payload['embeds'] = $params['embeds'];
        }
        
        if (isset($params['components'])) {
            $payload['components'] = $params['components'];
        }
        
        if (isset($params['allowed_mentions'])) {
            $payload['allowed_mentions'] = $params['allowed_mentions'];
        }
        
        if (isset($params['flags'])) {
            $payload['flags'] = $params['flags'];
        }
        
        if (isset($params['reply_ping']) && $params['reply_ping'] === false) {
            $payload['allowed_mentions'] = [
                'replied_user' => false
            ];
        }
        
        return $this->executeCurl($url, $payload);
    }
    
    /**
     * Execute CURL request to Discord API
     */
    private function executeCurl($url, $payload) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bot ' . $this->botToken,
                'Content-Type: application/json',
                'User-Agent: DiscordBot (https://github.com/discord/discord-api-docs, 1.0)'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception('CURL Error: ' . $error);
        }
        
        $decoded = json_decode($response, true);
        
        return [
            'http_code' => $httpCode,
            'response' => $decoded,
            'success' => $httpCode >= 200 && $httpCode < 300
        ];
    }
}

// Usage example
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['bot_token'])) {
            throw new Exception('Bot token is required');
        }
        
        $sender = new DiscordReplySender($input['bot_token']);
        $result = $sender->sendReply($input['params']);
        
        header('Content-Type: application/json');
        echo json_encode($result);
        
    } catch (Exception $e) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// CLI usage example
if (php_sapi_name() === 'cli' && isset($argv[1])) {
    try {
        $config = json_decode($argv[1], true);
        
        $sender = new DiscordReplySender($config['bot_token']);
        $result = $sender->sendReply($config['params']);
        
        echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

/*
Example usage (POST request):
{
    "bot_token": "YOUR_BOT_TOKEN",
    "params": {
        "channel_id": "123456789012345678",
        "content": "This is a reply message!",
        "message_reference": {
            "message_id": "987654321098765432",
            "channel_id": "123456789012345678",
            "guild_id": "111222333444555666"
        },
        "reply_ping": false,
        "embeds": [
            {
                "title": "Reply Embed",
                "description": "This is an embedded reply",
                "color": 3447003
            }
        ]
    }
}

CLI usage:
php discord_sender.php '{"bot_token":"bot_token","params":{"channel_id":"channel_id","content":"Reply!","message_reference":{"message_id":"1380090201208000562"}}}'
*/
?>