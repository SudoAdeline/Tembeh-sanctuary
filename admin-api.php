<?php
session_start();
header('Content-Type: application/json');

$PASS_FILE = __DIR__ . '/.admin_pass';
$DATA_DIR = __DIR__ . '/data';
$MEDIA_DIR = __DIR__ . '/media';

// Ensure directories exist
if (!is_dir($DATA_DIR)) mkdir($DATA_DIR, 0755, true);
if (!is_dir("$MEDIA_DIR/art")) mkdir("$MEDIA_DIR/art", 0755, true);
if (!is_dir("$MEDIA_DIR/music")) mkdir("$MEDIA_DIR/music", 0755, true);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// --- Public: check if password is set ---
if ($action === 'status') {
    echo json_encode(['hasPass' => file_exists($PASS_FILE)]);
    exit;
}

// --- Auth: setup password ---
if ($action === 'setup') {
    if (file_exists($PASS_FILE)) {
        http_response_code(403);
        echo json_encode(['error' => 'Password already set']);
        exit;
    }
    $pass = $_POST['password'] ?? '';
    if (strlen($pass) < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'Password required']);
        exit;
    }
    file_put_contents($PASS_FILE, password_hash($pass, PASSWORD_DEFAULT));
    $_SESSION['admin'] = true;
    echo json_encode(['ok' => true]);
    exit;
}

// --- Auth: login ---
if ($action === 'login') {
    if (!file_exists($PASS_FILE)) {
        http_response_code(400);
        echo json_encode(['error' => 'No password set']);
        exit;
    }
    $pass = $_POST['password'] ?? '';
    $hash = file_get_contents($PASS_FILE);
    if (password_verify($pass, $hash)) {
        $_SESSION['admin'] = true;
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Wrong password']);
    }
    exit;
}

// --- Auth: check session ---
if ($action === 'check') {
    echo json_encode(['loggedIn' => !empty($_SESSION['admin'])]);
    exit;
}

// --- Public: get data ---
if ($action === 'get') {
    $key = $_GET['key'] ?? '';
    $allowed = ['music', 'writing', 'art'];
    if (!in_array($key, $allowed)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid key']);
        exit;
    }
    $file = "$DATA_DIR/$key.json";
    if (!file_exists($file)) {
        echo '[]';
    } else {
        echo file_get_contents($file);
    }
    exit;
}

// --- All actions below require auth ---
if (empty($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Helper: save JSON data
function saveData($key, $items) {
    global $DATA_DIR;
    file_put_contents("$DATA_DIR/$key.json", json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Helper: load JSON data
function loadData($key) {
    global $DATA_DIR;
    $file = "$DATA_DIR/$key.json";
    if (!file_exists($file)) return [];
    $data = json_decode(file_get_contents($file), true);
    return is_array($data) ? $data : [];
}

// Helper: upload a file, return relative path
function uploadFile($fileInput, $subdir) {
    global $MEDIA_DIR;
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$fileInput];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safe = preg_replace('/[^a-z0-9_-]/', '', strtolower(pathinfo($file['name'], PATHINFO_FILENAME)));
    $name = $safe . '_' . time() . '.' . $ext;
    $dest = "$MEDIA_DIR/$subdir/$name";
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return "media/$subdir/$name";
    }
    return null;
}

// Helper: delete a media file
function deleteMediaFile($path) {
    if (!$path) return;
    $full = __DIR__ . '/' . $path;
    if (file_exists($full) && strpos(realpath($full), realpath(__DIR__ . '/media/')) === 0) {
        unlink($full);
    }
}

// --- Save music ---
if ($action === 'save_music') {
    $title = trim($_POST['title'] ?? '');
    if (!$title) {
        http_response_code(400);
        echo json_encode(['error' => 'Title required']);
        exit;
    }

    $items = loadData('music');
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $existing = ($index >= 0 && $index < count($items)) ? $items[$index] : [];

    $audioPath = uploadFile('audio', 'music');
    $coverPath = uploadFile('cover', 'music');

    // If replacing, delete old files
    if ($audioPath && !empty($existing['audio'])) deleteMediaFile($existing['audio']);
    if ($coverPath && !empty($existing['cover'])) deleteMediaFile($existing['cover']);

    $entry = [
        'title' => $title,
        'artist' => trim($_POST['artist'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'audio' => $audioPath ?? ($existing['audio'] ?? ''),
        'cover' => $coverPath ?? ($existing['cover'] ?? ''),
    ];

    if ($index >= 0 && $index < count($items)) {
        $items[$index] = $entry;
    } else {
        $items[] = $entry;
    }

    saveData('music', $items);
    echo json_encode(['ok' => true]);
    exit;
}

// --- Save writing ---
if ($action === 'save_writing') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if (!$title || !$body) {
        http_response_code(400);
        echo json_encode(['error' => 'Title and body required']);
        exit;
    }

    $items = loadData('writing');
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;

    $entry = [
        'title' => $title,
        'date' => trim($_POST['date'] ?? ''),
        'body' => $body,
    ];

    if ($index >= 0 && $index < count($items)) {
        $items[$index] = $entry;
    } else {
        $items[] = $entry;
    }

    saveData('writing', $items);
    echo json_encode(['ok' => true]);
    exit;
}

// --- Save art ---
if ($action === 'save_art') {
    $title = trim($_POST['title'] ?? '');
    if (!$title) {
        http_response_code(400);
        echo json_encode(['error' => 'Title required']);
        exit;
    }

    $items = loadData('art');
    $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
    $existing = ($index >= 0 && $index < count($items)) ? $items[$index] : [];

    $mediaPath = uploadFile('media', 'art');

    // If replacing, delete old file
    if ($mediaPath && !empty($existing['image'])) deleteMediaFile($existing['image']);

    if ($index < 0 && !$mediaPath) {
        http_response_code(400);
        echo json_encode(['error' => 'Media file required']);
        exit;
    }

    // Detect type from extension
    $path = $mediaPath ?? ($existing['image'] ?? '');
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $videoExts = ['mp4', 'webm', 'mov', 'avi', 'mkv'];
    $type = in_array($ext, $videoExts) ? 'video' : 'image';

    $entry = [
        'title' => $title,
        'description' => trim($_POST['description'] ?? ''),
        'image' => $path,
        'type' => $type,
    ];

    if ($index >= 0 && $index < count($items)) {
        $items[$index] = $entry;
    } else {
        $items[] = $entry;
    }

    saveData('art', $items);
    echo json_encode(['ok' => true]);
    exit;
}

// --- Delete item ---
if ($action === 'delete') {
    $key = $_POST['key'] ?? '';
    $index = intval($_POST['index'] ?? -1);
    $allowed = ['music', 'writing', 'art'];

    if (!in_array($key, $allowed) || $index < 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    $items = loadData($key);
    if ($index >= count($items)) {
        http_response_code(400);
        echo json_encode(['error' => 'Item not found']);
        exit;
    }

    $item = $items[$index];

    // Delete associated media files
    if ($key === 'music') {
        deleteMediaFile($item['audio'] ?? '');
        deleteMediaFile($item['cover'] ?? '');
    } elseif ($key === 'art') {
        deleteMediaFile($item['image'] ?? '');
    }

    array_splice($items, $index, 1);
    saveData($key, $items);
    echo json_encode(['ok' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
