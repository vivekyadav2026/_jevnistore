<?php
/**
 * ============================================================================
 * HOMEPAGE VIDEO REELS MANAGEMENT (admin/videos.php)
 * ============================================================================
 * Dedicated admin module for managing homepage mobile video reels and stories:
 * - Direct MP4 / WebM / MOV video uploads.
 * - Video reel title / label configuration.
 * - Live video preview player for uploaded reels.
 */
require_once 'includes/header.php';

// Handle Video Reels Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_videos'])) {
    
    // Save video titles / captions
    if (isset($_POST['video_captions']) && is_array($_POST['video_captions'])) {
        foreach ($_POST['video_captions'] as $v_id => $caption) {
            $caption_key = "homepage_video_caption_$v_id";
            $check_stmt = $conn->query("SELECT `key` FROM settings WHERE `key` = '$caption_key'");
            if ($check_stmt && $check_stmt->num_rows > 0) {
                $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
                $stmt->bind_param("ss", $caption, $caption_key);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)");
                $stmt->bind_param("ss", $caption_key, $caption);
                $stmt->execute();
            }
        }
    }

    // Handle File Uploads (video 1 to 5)
    for ($v = 1; $v <= 5; $v++) {
        $video_key = "homepage_video_$v";
        if (isset($_FILES[$video_key]) && $_FILES[$video_key]['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES[$video_key]['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['mp4', 'webm', 'mov', 'ogg'])) {
                // Ensure directory exists
                if (!file_exists('../assets/instagram/')) {
                    mkdir('../assets/instagram/', 0777, true);
                }
                
                $filename = 'insta_' . $v . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$video_key]['tmp_name'], '../assets/instagram/' . $filename)) {
                    $check_stmt = $conn->query("SELECT `key` FROM settings WHERE `key` = '$video_key'");
                    if ($check_stmt && $check_stmt->num_rows > 0) {
                        $stmt = $conn->prepare("UPDATE settings SET value = ? WHERE `key` = ?");
                        $stmt->bind_param("ss", $filename, $video_key);
                        $stmt->execute();
                    } else {
                        $stmt = $conn->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)");
                        $stmt->bind_param("ss", $video_key, $filename);
                        $stmt->execute();
                    }
                }
            }
        }
    }

    setFlash('Homepage video reels updated successfully.', 'success');
    redirect('videos.php');
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h3 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #f8fafc; text-transform: uppercase; letter-spacing: 1px;">Homepage Video Reels</h3>
        <p style="color: #94a3b8; font-size: 0.85rem; letter-spacing: 0.5px; margin-top: 4px;">Upload and manage video clips displayed in the homepage mobile video carousel</p>
    </div>
</div>

<form method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        
        <?php for ($v = 1; $v <= 5; $v++): 
            $video_file = getSetting("homepage_video_$v", "insta$v.mp4");
            $caption = getSetting("homepage_video_caption_$v", "Reel #$v");
            $video_url = (strpos($video_file, 'http') === 0) ? $video_file : BASE_URL . '/assets/instagram/' . $video_file;
        ?>
            <div style="background: var(--bg-secondary); padding: 24px; border-radius: 12px; border: 1px solid #334155; color: white; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 10px 25px rgba(0,0,0,0.3);">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 12px; margin-bottom: 18px;">
                        <h4 style="margin: 0; font-size: 1rem; color: #38bdf8; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">
                            <i data-lucide="video" style="width: 18px; height: 18px; vertical-align: middle; margin-right: 6px;"></i>
                            Video Reel #<?php echo $v; ?>
                        </h4>
                        <span style="font-size: 11px; background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 3px 8px; border-radius: 4px; font-weight: 600; text-transform: uppercase;">Active</span>
                    </div>

                    <!-- Video Preview Player -->
                    <div style="width: 100%; height: 260px; background: #0f172a; border-radius: 8px; overflow: hidden; margin-bottom: 18px; border: 1px solid #334155; position: relative;">
                        <video controls style="width: 100%; height: 100%; object-fit: cover;">
                            <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display:block; margin-bottom:6px; font-size:12px; color:#cbd5e1; font-weight:600; text-transform: uppercase; letter-spacing: 0.5px;">Reel Caption / Label</label>
                        <input type="text" name="video_captions[<?php echo $v; ?>]" class="form-control" value="<?php echo htmlspecialchars($caption); ?>" placeholder="e.g. Y2K Collection Drop" style="margin-bottom:0;">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display:block; margin-bottom:6px; font-size:12px; color:#cbd5e1; font-weight:600; text-transform: uppercase; letter-spacing: 0.5px;">Upload New Video File (MP4, WebM)</label>
                        <input type="file" name="homepage_video_<?php echo $v; ?>" class="form-control" accept="video/mp4,video/webm,video/mov" style="margin-bottom:0;">
                    </div>
                </div>

                <div style="font-size: 11px; color: #94a3b8; padding-top: 10px; border-top: 1px dashed #334155; display: flex; justify-content: space-between;">
                    <span>Current File:</span>
                    <strong style="color: #cbd5e1;"><?php echo htmlspecialchars($video_file); ?></strong>
                </div>
            </div>
        <?php endfor; ?>

    </div>

    <button type="submit" name="save_videos" class="btn" style="padding: 14px 40px; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; cursor: pointer; display: block;">
        Save All Video Reels
    </button>
</form>

<?php require_once 'includes/footer.php'; ?>
