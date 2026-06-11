<?php
require_once 'includes/header.php';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_settings'])) {
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
    }
    
    // Handle Hero Image Upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {
        $filename = uniqid() . '_' . basename($_FILES['hero_image']['name']);
        if (compressAndSaveImage($_FILES['hero_image']['tmp_name'], '../assets/' . $filename)) {
            $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE `key` = 'hero_image'");
            $stmt->bind_param("s", $filename);
            $stmt->execute();
        }
    }

    // Handle Logo Image Upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $filename = uniqid() . '_' . basename($_FILES['site_logo']['name']);
        if (compressAndSaveImage($_FILES['site_logo']['tmp_name'], '../assets/' . $filename)) {
            $check_stmt = $conn->query("SELECT `key` FROM settings WHERE `key` = 'site_logo'");
            if ($check_stmt && $check_stmt->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_logo'");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO settings (`key`, `value`) VALUES ('site_logo', ?)");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
            }
        }
    }
    
    setFlash('Settings updated successfully.', 'success');
    redirect('settings.php');
}
?>

<div style="margin-bottom: 2rem;">
    <h3 style="margin: 0;">Site Settings</h3>
    <p style="color: var(--text-secondary); font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase;">Manage storefront content, SEO configurations, and social integrations</p>
</div>

<form method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        
        <!-- General / SEO Configuration Card -->
        <div style="background: #111; padding: 25px; border-radius: 8px; border: 1px solid #333; color: white;">
            <h4 style="margin-top: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-bottom: 1px solid #222; padding-bottom: 10px; margin-bottom: 20px; color: var(--accent);">
                General & SEO
            </h4>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Site Title</label>
                <input type="text" name="settings[site_title]" class="form-control" value="<?php echo htmlspecialchars(getSetting('site_title')); ?>" required style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Site Logo (Upload to Replace)</label>
                <input type="file" name="site_logo" class="form-control" accept="image/*" style="margin-bottom:5px;">
                <?php if ($logo = getSetting('site_logo')): ?>
                    <div style="display:flex; align-items:center; gap: 8px;">
                        <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars($logo); ?>" style="max-width: 80px; max-height: 50px; object-fit: contain; border-radius: 4px; border: 1px solid #333; background: #fff;">
                        <span style="font-size: 11px; color: #666;"><?php echo htmlspecialchars($logo); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Meta Description</label>
                <textarea name="settings[site_description]" class="form-control" rows="3" required style="margin-bottom:0; font-family: inherit;"><?php echo htmlspecialchars(getSetting('site_description')); ?></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Announcement Bar</label>
                <input type="text" name="settings[announcement_bar]" class="form-control" value="<?php echo htmlspecialchars(getSetting('announcement_bar')); ?>" required style="margin-bottom:0;">
            </div>
        </div>

        <!-- Contact & Social Details Card -->
        <div style="background: #111; padding: 25px; border-radius: 8px; border: 1px solid #333; color: white;">
            <h4 style="margin-top: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-bottom: 1px solid #222; padding-bottom: 10px; margin-bottom: 20px; color: var(--accent);">
                Contact & Social Links
            </h4>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Instagram URL</label>
                <input type="url" name="settings[social_instagram]" class="form-control" value="<?php echo htmlspecialchars(getSetting('social_instagram')); ?>" style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Twitter URL</label>
                <input type="url" name="settings[social_twitter]" class="form-control" value="<?php echo htmlspecialchars(getSetting('social_twitter')); ?>" style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Contact Email</label>
                <input type="email" name="settings[contact_email]" class="form-control" value="<?php echo htmlspecialchars(getSetting('contact_email')); ?>" style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Contact Phone</label>
                <input type="text" name="settings[contact_phone]" class="form-control" value="<?php echo htmlspecialchars(getSetting('contact_phone')); ?>" style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">WhatsApp Number</label>
                <input type="text" name="settings[contact_whatsapp]" class="form-control" value="<?php echo htmlspecialchars(getSetting('contact_whatsapp')); ?>" style="margin-bottom:0;" placeholder="e.g. 1234567890">
            </div>
        </div>

        <!-- Homepage Hero Banner Card -->
        <div style="background: #111; padding: 25px; border-radius: 8px; border: 1px solid #333; color: white;">
            <h4 style="margin-top: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-bottom: 1px solid #222; padding-bottom: 10px; margin-bottom: 20px; color: var(--accent);">
                Homepage Hero Banner
            </h4>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Hero Subtitle</label>
                <input type="text" name="settings[hero_subtitle]" class="form-control" value="<?php echo htmlspecialchars(getSetting('hero_subtitle')); ?>" required style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Hero Title (HTML Supported)</label>
                <input type="text" name="settings[hero_title]" class="form-control" value="<?php echo htmlspecialchars(getSetting('hero_title')); ?>" required style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Hero Description</label>
                <textarea name="settings[hero_description]" class="form-control" rows="2" required style="margin-bottom:0; font-family: inherit;"><?php echo htmlspecialchars(getSetting('hero_description')); ?></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Hero Background Image (Upload to Replace)</label>
                <input type="file" name="hero_image" class="form-control" accept="image/*" style="margin-bottom:5px;">
                <?php if ($img = getSetting('hero_image')): ?>
                    <div style="display:flex; align-items:center; gap: 8px;">
                        <img src="<?php echo BASE_URL; ?>/assets/<?php echo htmlspecialchars($img); ?>" style="max-width: 80px; max-height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #333;">
                        <span style="font-size: 11px; color: #666;"><?php echo htmlspecialchars($img); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Brand Story Card -->
        <div style="background: #111; padding: 25px; border-radius: 8px; border: 1px solid #333; color: white;">
            <h4 style="margin-top: 0; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; border-bottom: 1px solid #222; padding-bottom: 10px; margin-bottom: 20px; color: var(--accent);">
                Brand Story
            </h4>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Story Heading</label>
                <input type="text" name="settings[brand_story_title]" class="form-control" value="<?php echo htmlspecialchars(getSetting('brand_story_title')); ?>" required style="margin-bottom:0;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px; font-size:12px; color:#888; text-transform: uppercase; letter-spacing: 0.5px;">Story Body Text</label>
                <textarea name="settings[brand_story_text]" class="form-control" rows="5" required style="margin-bottom:0; font-family: inherit;"><?php echo htmlspecialchars(getSetting('brand_story_text')); ?></textarea>
            </div>
        </div>
        
    </div>

    <button type="submit" name="save_settings" class="btn" style="padding: 15px 40px; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">
        Save Settings
    </button>
</form>

<?php require_once 'includes/footer.php'; ?>
