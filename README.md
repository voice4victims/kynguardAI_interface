# kynguardAI_interface
December 2025 version
# KynGuard AI - Heartwork Defense AI

AI-powered family law assistant with file uploads, conversation history, and n8n integration.

## 🚀 Features

- ✅ **AI-Powered Legal Guidance** - Integrated with n8n workflow automation
- ✅ **Secure File Upload** - Support for case documents (PDF, DOC, DOCX, TXT, JPG, PNG)
- ✅ **Conversation History** - Complete tracking of all interactions
- ✅ **User Dashboard** - Manage and review past conversations
- ✅ **Divi Builder Integration** - Custom module with visual editing
- ✅ **Responsive Design** - Optimized for mobile and desktop
- ✅ **Shortcode Support** - Works with any WordPress theme

## 📦 Installation

### Via WordPress Admin

1. Download the latest release as a ZIP file
2. Go to **Plugins → Add New → Upload Plugin**
3. Upload the ZIP file and click **Install Now**
4. Click **Activate Plugin**

### Via FTP

1. Upload the plugin folder to `/wp-content/plugins/heartwork-defense-ai/`
2. Go to **Plugins** in WordPress admin
3. Find "Heartwork Defense AI" and click **Activate**

### Initial Configuration

1. Go to **Settings → Heartwork AI**
2. Configure your n8n webhook settings:
   ```
   Webhook URL: http://n8n.heartworkdefense.com:5678/webhook/chat
   API Key Header: KynGuard-API-Key
   API Key Secret: hw_def_13de64f5_7K9mNpQ2xLvB3aR8wZsT6jHgYnC4F5E
   ```
3. Enable file uploads (optional)
4. Click **Test Webhook Connection** to verify
5. Click **Save Settings**

## 🎯 Usage

### Shortcode Method

Add to any page, post, or widget:

**Basic usage:**
```php
[heartwork_ai]
```

**With custom parameters:**
```php
[heartwork_ai 
    title="Legal AI Assistant" 
    primary_color="#2A3B6D" 
    user_bubble_color="#E0E7FF"
    chat_height="700px"
    show_header="on"]
```

**Dashboard shortcode:**
```php
[heartwork_dashboard]
```

### Divi Builder Method

1. Edit page with **Divi Builder**
2. Add a new **Module**
3. Search for **"Heartwork Defense AI"**
4. Customize settings in the module options:
   - Content → Header title, welcome message
   - Design → Colors, dimensions
5. Save and publish

### Available Shortcode Parameters

| Parameter | Default | Description |
|-----------|---------|-------------|
| `title` | "Heartwork Defense AI" | Header title |
| `primary_color` | "#2A3B6D" | Main brand color |
| `user_bubble_color` | "#E0E7FF" | User message bubble color |
| `welcome_message` | (default greeting) | Initial AI message |
| `show_header` | "on" | Show/hide header |
| `chat_height` | "600px" | Chat window height |

## 🔧 n8n Webhook Integration

### Webhook Payload Structure

Your n8n workflow receives this JSON payload:

```json
{
  "message": "User message or case details",
  "case_data": {
    "case_name": "Smith v. Jones",
    "case_type": "divorce",
    "case_summary": "Detailed case information...",
    "client_info": "Client name, DOB, etc."
  },
  "conversation_id": "unique-conversation-id",
  "files": [
    {
      "name": "document.pdf",
      "url": "https://yoursite.com/wp-content/uploads/heartwork-ai/...",
      "type": "application/pdf"
    }
  ],
  "timestamp": "2024-12-04 10:30:00",
  "user_ip": "192.168.1.1",
  "user_id": 1
}
```

### Expected n8n Response

Your workflow should return:

```json
{
  "response": "AI-generated response text here"
}
```

Or simply return plain text.

### Example n8n Workflow

1. **Webhook Node** - Receive POST request
2. **Function Node** - Extract data:
   ```javascript
   const message = $json.body.message;
   const caseData = $json.body.case_data;
   const files = $json.body.files;
   
   return {
     message,
     caseData,
     files
   };
   ```
3. **HTTP Request Node** - Call your AI service (OpenAI, Anthropic, etc.)
4. **Return Response** - Send back AI-generated text

## 📁 File Structure

```
heartwork-defense-ai/
├── heartwork-defense-ai.php          # Main plugin file
├── heartwork_interface_template.php  # UI template
├── README.md                         # Documentation
├── readme.txt                        # WordPress plugin readme
├── uninstall.php                     # Cleanup on uninstall
├── assets/
│   ├── heartwork-ai.js              # Frontend JavaScript
│   ├── admin.js                      # Admin scripts
│   └── admin.css                     # Admin styles
├── includes/
│   ├── class-database.php           # Database operations
│   ├── class-file-handler.php       # File upload management
│   ├── class-conversation-manager.php # Conversation CRUD
│   ├── HeartworkDefenseExtension.php # Divi extension
│   └── modules/
│       └── HeartworkAI/
│           ├── HeartworkAI.php      # Divi module PHP
│           ├── HeartworkAI.jsx      # Visual builder component
│           └── icon.svg             # Module icon
└── templates/
    └── dashboard.php                # User dashboard template
```

## 🗄️ Database Tables

The plugin creates three tables:

### `wp_hwai_conversations`
Stores conversation metadata
- `id` - Unique conversation identifier
- `user_id` - WordPress user ID
- `case_name` - Case title
- `case_type` - Type of legal case
- `case_summary` - Case description
- `client_info` - Client details
- `status` - Active/archived
- `created_at` / `updated_at` - Timestamps

### `wp_hwai_messages`
Stores individual messages
- `id` - Message ID
- `conversation_id` - Links to conversation
- `sender` - "user" or "ai"
- `message` - Message content
- `created_at` - Timestamp

### `wp_hwai_files`
Stores uploaded file information
- `id` - File ID
- `conversation_id` - Links to conversation
- `message_id` - Links to specific message
- `file_name` - Original filename
- `file_path` - Server file path
- `file_url` - Public URL
- `file_type` - MIME type
- `file_size` - Size in bytes
- `uploaded_at` - Timestamp

## 🔐 Security Features

- **Input Sanitization** - All user inputs sanitized
- **File Type Validation** - Only allowed file types accepted
- **File Size Limits** - Configurable max file size
- **SQL Injection Protection** - Prepared statements used
- **Authentication Required** - User login for personal data
- **API Key Authentication** - Secure webhook communication
- **Upload Directory Protection** - `.htaccess` prevents direct access
- **Nonce Verification** - CSRF protection on all AJAX calls

## 🎨 Customization

### Custom CSS

Add to your theme's `style.css` or Divi Theme Options:

```css
/* Primary colors */
.heartwork-ai-module {
    --primary-color: #2A3B6D;
    --user-bubble-color: #E0E7FF;
}

/* Chat bubbles */
.chat-bubble-user {
    background: #E0E7FF;
    border-radius: 1rem 1rem 0.25rem 1rem;
}

.chat-bubble-ai {
    background: white;
    border-radius: 1rem 1rem 1rem 0.25rem;
    border: 1px solid #D1D5DB;
}
```

### Custom JavaScript

Hook into events:

```javascript
jQuery(document).ready(function($) {
    // When interface is ready
    $(document).on('heartwork_interface_ready', function(e, interfaceId) {
        console.log('Interface ready:', interfaceId);
    });
    
    // Before message sent
    $(document).on('heartwork_before_send', function(e, message, conversationId) {
        console.log('Sending:', message);
    });
    
    // After response received
    $(document).on('heartwork_after_response', function(e, response) {
        console.log('Received:', response);
    });
});
```

## 📱 Mobile Responsiveness

The interface automatically adapts:

- **Desktop (1024px+)**: Two-column layout (form left, chat right)
- **Mobile (<1024px)**: Stacked layout (chat first, collapsible form)
- **Touch-friendly**: Large buttons and input areas
- **Optimized scrolling**: Chat messages scroll independently

## 🔄 Updates & Maintenance

### Check for Updates

1. Go to **Dashboard → Updates**
2. Look for "Heartwork Defense AI" in the plugin list
3. Click **Update Now** if available

### Backup Before Updating

1. Backup database (includes conversation history)
2. Backup `/wp-content/uploads/heartwork-ai/` (uploaded files)
3. Test on staging site first (recommended)

## 🐛 Troubleshooting

### Webhook Connection Fails

**Error:** "Error connecting to AI service"

**Solutions:**
1. Verify n8n is running and accessible
2. Check firewall isn't blocking port 5678
3. Test webhook manually with curl:
   ```bash
   curl -X POST http://n8n.heartworkdefense.com:5678/webhook/chat \
     -H "Content-Type: application/json" \
     -H "KynGuard-API-Key: hw_def_13de64f5_7K9mNpQ2xLvB3aR8wZsT6jHgYnC4F5E" \
     -d '{"message":"test"}'
   ```

### File Uploads Not Working

**Error:** "Failed to upload file"

**Solutions:**
1. Check folder permissions: `/wp-content/uploads/` should be writable (755)
2. Verify PHP upload limits in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Check "Enable File Uploads" is checked in plugin settings

### Messages Not Saving

**Error:** "Conversation not found"

**Solutions:**
1. Verify database tables exist (check phpMyAdmin)
2. Check database user has CREATE/INSERT permissions
3. Review browser console for JavaScript errors (F12)

### Divi Module Not Appearing

**Error:** Module not in Divi Builder

**Solutions:**
1. Clear Divi cache: Divi → Theme Options → Builder → Advanced
2. Verify Divi is updated to latest version
3. Deactivate and reactivate plugin

## 💻 System Requirements

- **WordPress:** 6.0 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.6 or higher
- **n8n:** Any version (for AI processing)
- **Divi:** Optional (for visual builder)

## 🤝 Support & Contributing

### Getting Help

- Check the [FAQ section](#troubleshooting)
- Review [n8n documentation](https://docs.n8n.io/)
- Create an issue on GitHub

### Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

GPL v2 or later

## 👥 Credits

**Developed by:** Heartwork Defense & SPM Advisors 
**Website:** https://heartworkdefense.com  
www.spmadvisors.net
**Support:** support@heartworkdefense.com

## 📝 Changelog

### Version 1.0.0 (2024-12-04)
- ✨ Initial release
- ✅ n8n webhook integration with authentication
- ✅ File upload with validation and security
- ✅ Conversation history and management
- ✅ User dashboard with search and filtering
- ✅ Divi Builder custom module
- ✅ Responsive mobile/desktop interface
- ✅ Shortcode support for all themes
- ✅ Database schema for conversations, messages, and files
- ✅ Comprehensive admin settings panel

## 🎯 Roadmap

### Planned Features
- [ ] Multi-language support
- [ ] Email notifications for new responses
- [ ] Export conversations to PDF
- [ ] Advanced analytics dashboard
- [ ] Integration with popular CRM systems
- [ ] Voice input support
- [ ] Document OCR for scanned files
- [ ] Real-time collaboration features

Update README with documentation
---

**Made with ❤️ for legal professionals**
