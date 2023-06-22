<?php
function showUpdateAlert($message) {
    echo "<div id='alert'>$message</div>";
    echo '<script>
            if (window.history.replaceState) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            
            setTimeout(function() {
                var alertDiv = document.getElementById("alert");
                if (alertDiv) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000); // Eliminar el div después de 5 segundos
          </script>';
}
?>