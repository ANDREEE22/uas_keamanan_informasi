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
      document.getElementById('result-display').value = ''; // Ganti dari textContent ke value
      document.getElementById('shift-value').textContent = '3';
      document.getElementById('shift').value = '3';
      setMode('enkripsi');
      
      // Hapus notifikasi
      const notification = document.querySelector('.notification');
      if (notification) {
        notification.remove();
      }
    }