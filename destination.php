<?php
if (!isset($_COOKIE["user"]))
    header('Location: ' . '/menu.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Links</title>
    <meta name="description" content="Links – A-Frame">
    <script src="js/aframe.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
    <script src="js/LegacyJSONLoader.js"></script>
    <script src="js/components/aframe-tooltip-component.js"></script>
    <script src="js/components/camera-position.js"></script>
    <script src="js/components/ground.js"></script>
    <script src="vendor/LegacyJSONLoader.js"></script>
    <script src="js/components/link-controls.js"></script>
    <script src="shaders/skyGradient.js"></script>
</head>
<body>
<?php include 'Data.php'; ?>
<a-scene fog="color: #241417; near: 0; far: 30;" raycaster="far: 100; objects: [link];" cursor="rayOrigin: mouse" camera-position>
    <a-assets>
        <?php for($i=0;$i<count($hotels[$_GET['id']]);$i++) { ?>
            <img id="thumb-<?php echo $hotels[$_GET['id']][$i]['slug']; ?>" crossOrigin="anonymous" src="<?php echo $hotels[$_GET['id']][$i]['logo']; ?>">
        <?php } ?>
    </a-assets>
    <?php for($i=0;$i<count($hotels[$_GET['id']]);$i++) { ?>
        <a-link href="hotel.php?city=<?php echo $_GET['id']; ?>&hotel=<?php echo $i; ?>" title="<?php echo $hotels[$_GET['id']][$i]['name']; ?>" position="-3.5 1.5 -1.0" image="#thumb-<?php echo $hotels[$_GET['id']][$i]['slug']; ?>"></a-link>
    <?php } ?>
    <a-entity id="sky"
              geometry="primitive: sphere; radius: 65;"
              material="shader: skyGradient; colorTop: #100117; colorBottom: #1F191A; side: back"></a-entity>
    <a-entity ground='url: https://cdn.aframe.io/link-traversal/models/groundCity.json'></a-entity>
    <a-entity light="type: point; color: #f4f4f4; intensity: 0.4; distance: 0" position="0 2 0"></a-entity>
    <a-entity id="leftHand" link-controls="hand: left"></a-entity>
    <a-entity id="rightHand" link-controls="hand: right"></a-entity>


</a-scene>
</body>
<script>
    if (annyang) {
        var commandos = {
            'atras': function() {
                window.location.href = "index.php";
            },
        };

        annyang.addCommands(commandos);

        annyang.setLanguage("es-MX");

        annyang.start();
    }
</script>
</html>
