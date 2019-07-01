<?php
if (!isset($_COOKIE["user"]))
    header('Location: ' . '/menu.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>3D Booking</title>
    <meta name="description" content="Hoteles Cancun">
    <script src="https://aframe.io/releases/0.9.1/aframe.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
    <script src="js/LegacyJSONLoader.js"></script>
    <script src="js/components/aframe-tooltip-component.js"></script>
    <script src="js/components/camera-position.js"></script>
    <script src="js/components/ground.js"></script>
    <script src="js/components/link-controls.js"></script>
    <script src="shaders/skyGradient.js"></script>
  </head>
  <body>
    <a-scene auto-enter-vr raycaster="far: 100; objects: [link];" cursor="rayOrigin: mouse" camera-position>
      <a-camera>
        <a-cursor></a-cursor>
      </a-camera>
      <a-assets>
        <img id="arena" crossOrigin="anonymous" src="img/floor.jpg">
        <img id="cancun" crossOrigin="anonymous" src="pics/home.jpg">
      </a-assets>
      <a-text font="kelsonsans" value="Di tu destino" width="6" position="-1 2 0"
              rotation="0 15 0"></a-text>
      <a-entity id="sky"
                geometry="primitive: sphere; radius: 10;"
                material="shader: flat; side: double; src: #cancun"></a-entity>
    </a-scene>

  </body>
  <script>
      AFRAME.registerComponent('auto-enter-vr', {
          init: function () {
              this.el.sceneEl.enterVR();
          }
      });
  </script>

  <?php include 'Data.php'; ?>
  <script>
      if (annyang) {
          var commandos = {
              'hola': function() {
                  alert("¡Hola!");
              },
              'atras': function() {
                  window.location.href = "menu.php";
              },
              <?php for($i=0;$i<count($destinations);$i++) { ?>
              '<?php echo $destinations[$i]['name']; ?>': function() {
                  window.location.href = "destination.php?id=<?php echo $i; ?>";
              },
              <?php } ?>
          };

          annyang.addCommands(commandos);

          annyang.setLanguage("es-MX");

          annyang.start();
      }
  </script>
</html>
