document.getElementById('captcha').innerHTML = "<?php echo $_SESSION['cap1']; ?> + <?php echo $_SESSION['cap2']; ?> = ";
document.getElementById('tocken').value = "<?php echo $_SESSION['token']; ?>";
