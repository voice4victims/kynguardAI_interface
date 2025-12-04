<?php
/**
 * Heartwork Defense AI Interface Template
 * 
 * Responsive interface compatible with Divi and n8n webhook integration
 * Works seamlessly on mobile and desktop
 */

if (!defined('ABSPATH')) exit;

// Get settings
$settings = array(
    'title' => isset($atts['title']) ? $atts['title'] : 'Heartwork Defense AI',
    'primary_color' => isset($atts['primary_color']) ? $atts['primary_color'] : '#2A3B6D',
    'user_bubble_color' => isset($atts['user_bubble_color']) ? $atts['user_bubble_color'] : '#E0E7FF',
    'welcome_message' => isset($atts['welcome_message']) ? $atts['welcome_message'] : 'Hello! I am Heartwork Defense AI. Please provide case details or ask a specific question about family law.',
    'show_header' => isset($atts['show_header']) ? $atts['show_header'] : 'on',
    'chat_height' => isset($atts['chat_height']) ? $atts['chat_height'] : '600px',
    'conversation_id' => isset($atts['conversation_id']) ? $atts['conversation_id'] : ''
);

$text_domain = 'heartwork-ai';
$user_can_submit = is_user_logged_in();
?>

<div class="heartwork-ai-module" 
     data-conversation-id="<?php echo esc_attr($settings['conversation_id']); ?>"
     style="--primary-color: <?php echo esc_attr($settings['primary_color']); ?>; --user-bubble-color: <?php echo esc_attr($settings['user_bubble_color']); ?>; --secondary-text-color: <?php echo esc_attr($settings['primary_color']); ?>;">
    
    <?php if ($settings['show_header'] === 'on'): ?>
    <header class="primary-bg shadow-md mb-8" style="position: sticky; top: 0; z-index: 50; padding: 1rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <h1 class="primary-text" style="font-size: 1.5rem; font-weight: bold; margin: 0; color: white;"><?php echo esc_html($settings['title']); ?></h1>
            <?php if (is_user_logged_in()): ?>
            <a href="<?php echo admin_url('admin.php?page=heartwork-ai-dashboard'); ?>" 
               style="font-size: 0.875rem; text-decoration: none; color: white; hover: text-decoration: underline;">
                View All Conversations
            </a>
            <?php endif; ?>
        </div>
    </header>
    <?php endif; ?>
    
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Desktop Layout -->
        <div class="hwai-desktop" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                
                <!-- Form Column -->
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: var(--secondary-text-color);">
                        Enter Case Details
                    </h2>
                    
                    <form class="heartwork-form" style="display: flex; flex-direction: column; gap: 1rem;">
                        <?php wp_nonce_field('heartwork_ai_nonce', 'heartwork_nonce'); ?>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Name *
                            </label>
                            <input name="case_name" 
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                   type="text" 
                                   placeholder="e.g., Smith v. Jones" 
                                   required />
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Type *
                            </label>
                            <select name="case_type" 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                    required>
                                <option value="">Select a case type...</option>
                                <option value="divorce">Divorce</option>
                                <option value="child_custody">Child Custody</option>
                                <option value="child_support">Child Support</option>
                                <option value="spousal_support">Spousal Support</option>
                                <option value="property_division">Property Division</option>
                                <option value="domestic_violence">Domestic Violence</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Summary *
                            </label>
                            <textarea name="case_summary" 
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; font-family: inherit; box-sizing: border-box;" 
                                      rows="4" 
                                      placeholder="Enter case details..." 
                                      required></textarea>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Client Information
                            </label>
                            <input name="client_info" 
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                   type="text" 
                                   placeholder="Client Name, DOB, etc." />
                        </div>
                        
                        <?php if (get_option('heartwork_enable_file_upload', '1') === '1'): ?>
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Upload Documents
                            </label>
                            <input type="file" 
                                   name="case_files[]" 
                                   class="case-file-input" 
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                   multiple 
                                   accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" />
                            <small style="color: #6B7280; font-size: 0.75rem;">
                                Max size: <?php echo esc_html(get_option('heartwork_max_file_size', '10')); ?>MB per file
                            </small>
                            <div class="uploaded-files-list" style="margin-top: 0.5rem;"></div>
                        </div>
                        <?php endif; ?>
                        
                        <button class="primary-bg primary-text" 
                                style="width: 100%; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; cursor: pointer; font-weight: 500; background-color: var(--primary-color); color: white;" 
                                type="submit">
                            <span class="submit-text">Submit to AI</span>
                            <span class="loading-spinner" style="display: none;">Processing...</span>
                        </button>
                    </form>
                </div>
                
                <!-- Chat Column -->
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; flex-direction: column; height: <?php echo esc_attr($settings['chat_height']); ?>;">
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; color: var(--secondary-text-color);">
                        AI Guidance
                    </h2>
                    
                    <div class="chat-messages" style="flex: 1; overflow-y: auto; margin-bottom: 1rem; padding-right: 0.5rem;">
                        <div style="display: flex; justify-content: flex-start; margin-bottom: 1rem;">
                            <div class="chat-bubble-ai" style="padding: 0.75rem; max-width: 65%; background: white; border-radius: 1rem 1rem 1rem 0.25rem; border: 1px solid #D1D5DB;">
                                <p style="font-size: 0.875rem; margin: 0; color: #1F2937;">
                                    <?php echo esc_html($settings['welcome_message']); ?>
                                </p>
                                <p style="font-size: 0.75rem; color: #6B7280; margin: 0.25rem 0 0 0; text-align: right;">
                                    <?php echo current_time('g:i A'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #E5E7EB; padding-top: 1rem;">
                        <form class="chat-form">
                            <div style="display: flex; align-items: flex-end;">
                                <textarea class="chat-input" 
                                          style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem 0 0 0.375rem; resize: none; font-family: inherit; box-sizing: border-box;" 
                                          rows="2" 
                                          placeholder="Type your question here..."
                                          <?php echo !$user_can_submit ? 'disabled' : ''; ?>></textarea>
                                <button type="submit" 
                                        class="primary-bg primary-text" 
                                        style="padding: 0.75rem 1rem; border: none; border-radius: 0 0.375rem 0.375rem 0; cursor: pointer; background-color: var(--primary-color); color: white;">
                                    ▶
                                </button>
                            </div>
                            <?php if (get_option('heartwork_enable_file_upload', '1') === '1' && $user_can_submit): ?>
                            <div style="margin-top: 0.5rem;">
                                <label style="display: inline-flex; align-items: center; cursor: pointer; font-size: 0.875rem; color: #4B5563;">
                                    📎 Attach file
                                    <input type="file" class="chat-file-input" style="display: none;" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" />
                                </label>
                                <div class="chat-files-preview" style="margin-top: 0.5rem;"></div>
                            </div>
                            <?php endif; ?>
                            <?php if (!$user_can_submit): ?>
                            <p style="margin-top: 0.5rem; text-align: center; color: #6B7280; font-size: 0.875rem;">
                                <a href="<?php echo wp_login_url(get_permalink()); ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">
                                    Log in
                                </a> to chat with AI
                            </p>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Mobile Layout -->
        <div class="hwai-mobile" style="display: block;">
            <!-- Chat Section (shown first on mobile) -->
            <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; min-height: 400px; display: flex; flex-direction: column;">
                <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--secondary-text-color);">
                    AI Guidance
                </h2>
                
                <div class="chat-messages" style="flex: 1; overflow-y: auto; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: flex-start; margin-bottom: 1rem;">
                        <div class="chat-bubble-ai" style="padding: 0.75rem; max-width: 85%; background: white; border-radius: 1rem 1rem 1rem 0.25rem; border: 1px solid #D1D5DB;">
                            <p style="font-size: 0.875rem; margin: 0; color: #1F2937;">
                                <?php echo esc_html($settings['welcome_message']); ?>
                            </p>
                            <p style="font-size: 0.75rem; color: #6B7280; margin: 0.25rem 0 0 0; text-align: right;">
                                <?php echo current_time('g:i A'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div style="border-top: 1px solid #E5E7EB; padding-top: 1rem;">
                    <form class="chat-form">
                        <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
                            <textarea class="chat-input" 
                                      style="flex: 1; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; resize: none; font-family: inherit; box-sizing: border-box;" 
                                      rows="2" 
                                      placeholder="Type your question..."></textarea>
                            <button type="submit" 
                                    class="primary-bg primary-text" 
                                    style="padding: 0.75rem 1rem; border: none; border-radius: 0.375rem; cursor: pointer; background-color: var(--primary-color); color: white; white-space: nowrap;">
                                Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Form Section (collapsible on mobile) -->
            <div style="background: white; padding: 1rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <details>
                    <summary style="cursor: pointer; font-size: 1.125rem; font-weight: 600; padding: 0.5rem 0; list-style: none; color: var(--secondary-text-color); user-select: none;">
                        📋 Case Details Form ▼
                    </summary>
                    <form class="heartwork-form" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                        <?php wp_nonce_field('heartwork_ai_nonce', 'heartwork_nonce'); ?>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Name *
                            </label>
                            <input name="case_name" 
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                   type="text" 
                                   placeholder="e.g., Smith v. Jones" 
                                   required />
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Type *
                            </label>
                            <select name="case_type" 
                                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                    required>
                                <option value="">Select type...</option>
                                <option value="divorce">Divorce</option>
                                <option value="child_custody">Child Custody</option>
                                <option value="child_support">Child Support</option>
                                <option value="spousal_support">Spousal Support</option>
                                <option value="property_division">Property Division</option>
                                <option value="domestic_violence">Domestic Violence</option>
                            </select>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Case Summary *
                            </label>
                            <textarea name="case_summary" 
                                      style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; font-family: inherit; box-sizing: border-box;" 
                                      rows="3" 
                                      placeholder="Enter case details..." 
                                      required></textarea>
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                Client Information
                            </label>
                            <input name="client_info" 
                                   style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #D1D5DB; border-radius: 0.375rem; box-sizing: border-box;" 
                                   type="text" 
                                   placeholder="Client Name, DOB, etc." />
                        </div>
                        
                        <button class="primary-bg primary-text" 
                                style="width: 100%; padding: 0.75rem 1rem; border-radius: 0.375rem; border: none; cursor: pointer; font-weight: 500; background-color: var(--primary-color); color: white;" 
                                type="submit">
                            <span class="submit-text">Submit to AI</span>
                            <span class="loading-spinner" style="display: none;">Processing...</span>
                        </button>
                    </form>
                </details>
            </div>
        </div>
        
    </div>
    
    <!-- Responsive CSS -->
    <style>
        @media (min-width: 1024px) {
            .hwai-desktop { display: block !important; }
            .hwai-mobile { display: none !important; }
        }
        @media (max-width: 1023px) {
            .hwai-desktop { display: none !important; }
            .hwai-mobile { display: block !important; }
        }
        
        /* Scrollbar styling */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        .chat-messages::-webkit-scrollbar-track {
            background: #F3F4F6;
            border-radius: 3px;
        }
        .chat-messages::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 3px;
        }
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }
        
        /* User bubble style */
        .chat-bubble-user {
            background-color: var(--user-bubble-color) !important;
            border-radius: 1rem 1rem 0.25rem 1rem !important;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes bounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in;
        }
    </style>
</div>

<?php do_action('heartwork_interface_after_render', $atts); ?>
