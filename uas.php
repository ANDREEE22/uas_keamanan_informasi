<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Caesar Cipher</title>
  <link rel="stylesheet" href="style/uu.css">
  <script src="js/hh.js"></script>
</head>
<body>
  <div class="container">
    <button class="back-btn" onclick="window.location.href='index.php'">🔙</button>
    <h2>CAESAR CIPHER</h2>
    
    <?php
    $result = '';
    $notification = '';
    $currentMode = 'enkripsi';
    $currentShift = 3;
    $currentText = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $currentMode = $_POST['mode'] ?? 'enkripsi';
        $currentText = $_POST['text'] ?? '';
        $currentShift = isset($_POST['shift']) ? (int)$_POST['shift'] : 3;
        
        if (!empty($currentText) && $currentShift > 0) {
            $result = '';
            $shift = $currentShift % 95; // 95 printable characters (32-126)
            
            for ($i = 0; $i < strlen($currentText); $i++) {
                $char = $currentText[$i];
                $code = ord($char);
                
                if ($code >= 32 && $code <= 126) {
                    if ($currentMode === "enkripsi") {
                        $newCode = 32 + ($code - 32 + $shift) % 95;
                    } else {
                        $newCode = 32 + ($code - 32 - $shift + 95) % 95;
                    }
                    $result .= chr($newCode);
                } else {
                    $result .= $char;
                }
            }
            
            // Pastikan $result terisi sebelum dikirim ke Telegram
            if (!empty($result)) {
                // Kirim ke Telegram
                $token = '7588665296:AAHdUrtkpLRA-U_G-Q-a2MzGg43MGdQa7G4';
                $chatId = '1154143567';
                $pesan = "🔐  *Algoritma Caesar Cipher*".PHP_EOL
                       . "=========================".PHP_EOL 
                       . "◉ Mode: ".($currentMode === "enkripsi" ? "Enkripsi" : "Dekripsi").PHP_EOL 
                       . "◉ Teks: $currentText".PHP_EOL
                       . "◉ Shift: $currentShift".PHP_EOL
                       . "◉ Hasil: $result".PHP_EOL
                       . "=========================".PHP_EOL;
                $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($pesan);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                
                $responseData = json_decode($response, true);
                if ($responseData['ok'] ?? false) {
                    $notification = '<div class="notification success">✅ Pesan berhasil dikirim ke Telegram!</div>';
                } else {
                    $notification = '<div class="notification error">❌ Gagal mengirim pesan ke Telegram.</div>';
                }
            }
        }
    }
    ?>
    
    <?php echo $notification; ?>
    
    <form method="post" id="cipherForm">
      <div class="mode-selector">
        <div class="mode-option <?php echo $currentMode === 'enkripsi' ? 'active' : ''; ?>" onclick="setMode('enkripsi')">Enkripsi</div>
        <div class="mode-option <?php echo $currentMode === 'dekripsi' ? 'active' : ''; ?>" onclick="setMode('dekripsi')">Dekripsi</div>
      </div>
      <input type="hidden" id="mode" name="mode" value="<?php echo $currentMode; ?>">

      <div class="section-title">Pesan Anda</div>
      <textarea class="text-box" id="text" name="text" placeholder="Ketik pesan rahasia Anda di sini..."><?php echo htmlspecialchars($currentText); ?></textarea>

      <div class="section-title">Nilai Geser</div>
      <div class="shift-control">
        <button type="button" class="shift-button" onclick="changeShift(-1)">-</button>
        <div class="shift-value" id="shift-value"><?php echo $currentShift; ?></div>
        <button type="button" class="shift-button" onclick="changeShift(1)">+</button>
      </div>
      <input type="hidden" id="shift" name="shift" value="<?php echo $currentShift; ?>">

      <div class="section-title">Hasil</div>
      <!-- Ganti dari div ke textarea untuk hasil -->
      <textarea class="text-box" id="result-display" name="result" readonly><?php echo htmlspecialchars($result); ?></textarea>

      <div class="button-group">
        <button type="submit" class="action-button process-btn">Proses & Kirim</button>
        <button type="button" class="action-button reset-btn" onclick="resetForm()">Reset</button>
      </div>
    </form>
  </div>
</body>
</html>