  let timer;
    let timeRemaining = 900; 
    let isPaused = false;

  
    function startTimer() {
      updateTimer(); 
      timer = setInterval(updateTimer, 1000); 
    }

 
    function updateTimer() {
      if (!isPaused) {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        const timerDisplay = document.getElementById('timer');
        timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeRemaining <= 0) {
          clearInterval(timer);
          timerDisplay.textContent = 'Tempo completo!';
        }
        
        timeRemaining--;
      }
    }


    function resetTimer() {
      clearInterval(timer);
      timeRemaining = 900;
      isPaused = false;
      updateTimer(); 
    }

   
    function pauseTimer() {
      isPaused = true;
    }

    function resumeTimer() {
      isPaused = false;
    }
