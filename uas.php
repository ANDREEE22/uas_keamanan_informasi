<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Caesar Cipher - Telegram Auto</title>
  <style>
    * { 
      box-sizing: border-box; 
      font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    
    body { 
      margin: 0; 
      padding: 20px; 
      background: #f8f9fa;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    
    .container {
      max-width: 500px;
      width: 100%;
      margin: 20px auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      position: relative;
    }
    
    h2 { 
      text-align: center; 
      color: #2c3e50;
      margin-bottom: 25px;
      font-weight: 600;
      font-size: 24px;
    }
    
    .mode-selector {
      display: flex;
      margin-bottom: 25px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .mode-option {
      flex: 1;
      padding: 12px;
      text-align: center;
      background: #f1f3f5;
      color: #495057;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .mode-option.active {
      background: linear-gradient(135deg, #2ecc71, #3498db);
      color: white;
    }
    
    .section-title {
      color: #495057;
      font-weight: 600;
      margin-bottom: 10px;
      font-size: 16px;
    }
    
    .text-box {
      width: 100%;
      margin-bottom: 25px;
      padding: 15px;
      border-radius: 12px;
      border: 1px solid #e9ecef;
      font-size: 15px;
      transition: all 0.3s;
      background-color: #f8f9fa;
      min-height: 120px;
      resize: vertical;
    }
    
    .text-box:focus {
      outline: none;
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
      background-color: white;
    }
    
    .shift-control {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .shift-value {
      flex: 1;
      text-align: center;
      font-size: 18px;
      font-weight: 600;
      color: #2c3e50;
    }
    
    .shift-button {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #f1f3f5;
      border: none;
      font-size: 18px;
      font-weight: bold;
      color: #495057;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    
    .shift-button:hover {
      background: #e9ecef;
    }
    
    .result-box {
      width: 100%;
      margin-bottom: 25px;
      padding: 15px;
      border-radius: 12px;
      border: 1px solid #e9ecef;
      font-size: 15px;
      background-color: #f1f8fe;
      min-height: 120px;
      color: #2c3e50;
    }
    
    .button-group {
      display: flex;
      gap: 15px;
    }
    
    .action-button {
      flex: 1;
      padding: 14px;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      color: white;
      transition: all 0.3s;
    }
    
    .process-btn {
      background: linear-gradient(135deg, #2ecc71, #3498db);
      box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
    }
    
    .reset-btn {
      background: linear-gradient(135deg, #e74c3c, #f39c12);
      box-shadow: 0 4px 15px rgba(231, 76, 60, 0.1);
    }
    
    .action-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    
    .action-button:active {
      transform: translateY(0);
    }
    
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      background: #f1f3f5;
      color: #495057;
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .back-btn:hover {
      background: #e9ecef;
    }
    
    .notification {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 12px;
      font-weight: 600;
      text-align: center;
      animation: fadeIn 0.5s;
    }
    
    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 600px) {
      .container {
        padding: 20px;
      }
      
      .button-group {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <button class="back-btn" onclick="window.location.href='index.php'">←</button>
    <h2>Encryption Mode</h2>
    
    <?php
    $result = '';
    $notification = '';
    $currentMode = 'encrypt';
    $currentShift = 3;
    $currentText = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $currentMode = $_POST['mode'] ?? 'encrypt';
      $currentText = $_POST['text'] ?? '';
      $currentShift = isset($_POST['shift']) ? (int)$_POST['shift'] : 3;
      
      if (!empty($currentText) && $currentShift > 0) {
        $result = '';
        $shift = $currentShift % 26;
        
        for ($i = 0; $i < strlen($currentText); $i++) {
          $char = $currentText[$i];
          $code = ord($char);
          
          if (preg_match('/[a-z]/i', $char)) {
            $base = ($code >= 65 && $code <= 90) ? 65 : 97;
            $offset = ($currentMode === "encrypt")
              ? ($code - $base + $shift) % 26
              : ($code - $base - $shift + 26) % 26;
            $result .= chr($base + $offset);
          } else {
            $result .= $char;
          }
        }
        
        // Kirim ke Telegram
        $token = '7588665296:AAHdUrtkpLRA-U_G-Q-a2MzGg43MGdQa7G4';
        $chatId = '1154143567';
        $pesan = "🛡️ *Caesar Cipher Bot*\nMode: " . ($currentMode === "encrypt" ? "Enkripsi" : "Dekripsi") . "\nTeks: $currentText\nShift: $currentShift\n\nHasil: $result";
        $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($pesan);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        if ($responseData['ok'] ?? false) {
          $notification = '<div class="notification success">✅ Message successfully sent to Telegram!</div>';
        } else {
          $notification = '<div class="notification error">❌ Failed to send message to Telegram.</div>';
        }
      }
    }
    ?>
    
    <?php echo $notification; ?>
    
    <form method="post" id="cipherForm">
      <div class="mode-selector">
        <div class="mode-option <?php echo $currentMode === 'encrypt' ? 'active' : ''; ?>" onclick="setMode('encrypt')">Encrypt</div>
        <div class="mode-option <?php echo $currentMode === 'decrypt' ? 'active' : ''; ?>" onclick="setMode('decrypt')">Decrypt</div>
      </div>
      <input type="hidden" id="mode" name="mode" value="<?php echo $currentMode; ?>">

      <div class="section-title">Your Message</div>
      <textarea class="text-box" id="text" name="text" placeholder="Type your secret message here..."><?php echo htmlspecialchars($currentText); ?></textarea>

      <div class="section-title">Shift Value</div>
      <div class="shift-control">
        <button type="button" class="shift-button" onclick="changeShift(-1)">-</button>
        <div class="shift-value" id="shift-value"><?php echo $currentShift; ?></div>
        <button type="button" class="shift-button" onclick="changeShift(1)">+</button>
      </div>
      <input type="hidden" id="shift" name="shift" value="<?php echo $currentShift; ?>">

      <div class="section-title">Result</div>
      <div class="result-box" id="result-display"><?php echo htmlspecialchars($result); ?></div>
      
      <div class="button-group">
        <button type="submit" class="action-button process-btn">Process & Send</button>
        <button type="button" class="action-button reset-btn" onclick="resetForm()">Reset</button>
      </div>
    </form>
  </div>

  <script>
    function setMode(mode) {
      document.querySelectorAll('.mode-option').forEach(opt => {
        opt.classList.toggle('active', opt.textContent.toLowerCase() === mode);
      });
      document.getElementById('mode').value = mode;
    }
    
    function changeShift(amount) {
      const shiftDisplay = document.getElementById('shift-value');
      const shiftInput = document.getElementById('shift');
      let value = parseInt(shiftDisplay.textContent) + amount;
      value = Math.max(1, Math.min(25, value));
      shiftDisplay.textContent = value;
      shiftInput.value = value;
    }
    
    function resetForm() {
      document.getElementById('text').value = '';
      document.getElementById('result-display').textContent = '';
      document.getElementById('shift-value').textContent = '3';
      document.getElementById('shift').value = '3';
      setMode('encrypt');
    }
  </script>
</body>
</html>