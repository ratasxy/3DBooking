<?php
if (!isset($_COOKIE["user"]))
    header('Location: ' . '/menu.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WebVR - Aframe - VR walkthrough</title>
    <meta name="description" content="WebVR - Aframe - VR walkthrough">
    <meta name="author" content="Kumar Ahir - VR Designer">
    <link rel="stylesheet" type="text/css" href="js/modal.css">
    <script src="js/aframe.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
    <script src="https://unpkg.com/aframe-animation-component@^4.1.2/dist/aframe-animation-component.min.js"></script>
    <script src="https://unpkg.com/aframe-look-at-component@0.5.1/dist/aframe-look-at-component.min.js"></script>
    <script src="https://rawgit.com/urish/aframe-camera-events/master/index.js"></script>
    <script src="https://unpkg.com/aframe-text-geometry-component@^0.5.0/dist/aframe-text-geometry-component.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/notify.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <style>
        .answered{
            background-color: #b2dfdb;
        }
    </style>
    <script>
        AFRAME.registerComponent('hotspots',{
            init:function(){
                this.el.addEventListener('reloadspots',function(evt){

                    //get the entire current spot group and scale it to 0
                    var currspotgroup=document.getElementById(evt.detail.currspots);
                    currspotgroup.setAttribute("scale","0 0 0");

                    //get the entire new spot group and scale it to 1
                    var newspotgroup=document.getElementById(evt.detail.newspots);
                    newspotgroup.setAttribute("scale","1 1 1");
                });
            }
        });
        AFRAME.registerComponent('spot',{
            schema:{
                linkto:{type:"string",default:""},
                spotgroup:{type:"string",default:""}
            },
            init:function(){

                //add image source of hotspot icon
                this.el.setAttribute("src","#hotspot");
                //make the icon look at the camera all the time
                this.el.setAttribute("look-at","#cam");

                var data=this.data;

                this.el.addEventListener('click',function(){
                    console.log("clickkkk en hotspot");
                    //set the skybox source to the new image as per the spot
                    console.log("enlazando a " + data.linkto);
                    var sky=document.getElementById("skybox");
                    sky.setAttribute("material","shader: flat; side: double; src: " + data.linkto);

                    var spotcomp=document.getElementById("spots");
                    var currspots=this.parentElement.getAttribute("id");
                    //create event for spots component to change the spots data
                    spotcomp.emit('reloadspots',{newspots:data.spotgroup,currspots:currspots});

                    conn.send(btoa(JSON.stringify({type:"portal", room:"<?echo $_GET['sala']; ?>", alias:"<?echo $_GET['alias']; ?>", portal:data.linkto})));
                });
            }
        });
    </script>
</head>
<body>
<?php include 'Data.php'; ?>
<a-scene background="color: #ECECEC">
    <a-assets>
        <?php for($i=0;$i<count($hotels[$_GET['city']][$_GET['hotel']]['rooms'][$_GET['room']]['photos']);$i++) { ?>
            <img id="point<?php echo $i; ?>" src="<?php echo $hotels[$_GET['city']][$_GET['hotel']]['rooms'][$_GET['room']]['photos'][$i]['image']; ?>"/>
        <?php } ?>

        <img id="hotspot" src="img/hotspot.png"/>
    </a-assets>

    <a-entity id="spots" hotspots>
        <a-entity id="group-point1">
            <?php for($i=0;$i<count($hotels[$_GET['city']][$_GET['hotel']]['rooms'][$_GET['room']]['photos']);$i++) { ?>
                <a-image spot="linkto:#point<?php echo $i; ?>;spotgroup:group-point1" position="-4 0 <?php echo 10 + ($i * 5); ?>"></a-image>
            <?php } ?>
        </a-entity>
    </a-entity>

    <a-entity id="skybox" src="#point0" geometry="primitive: sphere; radius:20;" material="shader: flat; side: double; src: #point0">

    </a-entity>

    <a-camera id="cam" look-controls movement-controls position-listener>
        <a-entity cursor="fuse:true;fuseTimeout:2000"
                  geometry="primitive:ring;radiusInner:0.01;radiusOuter:0.02"
                  position="0 0 -1.8"
                  material="shader:flat;color:#ff0000"
                  animation__mouseenter="property:scale;to:3 3 3;startEvents:mouseenter;endEvents:mouseleave;dir:reverse;dur:2000;loop:1">
        </a-entity>
    </a-camera>

    

    <script>

        if (annyang) {
            var commandos = {
                'hola': function() {
                    alert("¡Hola!");
                },
                'atras': function() {
                    window.location.href = "hotel.php?city=<?php echo $_GET['city'];?>&hotel=<?php echo $_GET['hotel'];?>";
                },
                'crear sala': function() {
                    var sala = Math.floor((Math.random() * 100000) + 100);
                    window.location.href = document.URL + "&sala=" + sala;
                },
                'entrar sala *sala': function(sala) {
                    window.location.href = document.URL + "&sala=" + sala;
                },
                'usuario *user': function(user) {;
                    window.location.href = document.URL + "&alias=" + user;
                },
                'pregunta *question': function(question) {
                    var sceneEl = document.querySelector('a-scene');
                    var camera = sceneEl.querySelector('a-camera')
                    var newo = document.createElement('a-text');
                    newo.setAttribute('position', camera.getAttribute('position'));
                    newo.setAttribute('text-geometry', 'value:' + question + ";");
                    //newo.setAttribute('value', question);
                    conn.send(btoa(JSON.stringify({type:"question", room:"<?echo $_GET['sala']; ?>", alias:"<?echo $_GET['alias']; ?>", position:camera.getAttribute('position'), question:question})));
                    $.notify("Se agrego la pregunta");
                    sceneEl.appendChild(newo);
                },
            };

            annyang.addCallback('soundstart', function () {
                $.notify("Escuchando", "info");
            });

            annyang.addCallback('resultNoMatch', function () {
                $.notify("Lo siento no te entendi", "warning");
            });

            annyang.addCallback('error', function(err) {
                console.log('There was an error in Annyang!',err);
            });

            annyang.addCommands(commandos);

            annyang.setLanguage("es-MX");

            annyang.start();
        }
    </script>

    <script>

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        <?php if(isset($_GET['alias'])): ?>

        var conn = new WebSocket('wss://172.20.10.2/wss/');
        conn.onopen = function(e) {
            console.log("Connection established!");
            var login = {type:"login", room:"<?echo $_GET['sala']; ?>", alias:"<?echo $_GET['alias']; ?>"};
            conn.send(btoa(JSON.stringify(login)));

            document.getElementById('cam')
                .addEventListener('positionChanged', e => {
                    conn.send(btoa(JSON.stringify({type:"move", room:"<?echo $_GET['sala']; ?>", alias:"<?echo $_GET['alias']; ?>", position:e.detail})));
                    console.log('Nueva position:', e.detail);
                });
        };

        function sendQuestion(){
            $(".modalDialog").css({"opacity":"0","pointer-events":"none"});

            value = $('#question').val();

            $('#question').val('');

            var sceneEl = document.querySelector('a-scene');
            var camera = sceneEl.querySelector('a-camera')
            var position = camera.getAttribute('position');

            conn.send(btoa(JSON.stringify({type:"question", room:"<?echo $_GET['sala']; ?>", alias:"<?echo $_GET['alias']; ?>", position:position, question:value})));

            var newo = document.createElement('a-entity');
            newo.setAttribute('position', position);
            newo.setAttribute('text-geometry', 'value:' + value + ";");
            //newo.setAttribute('value', value);
            sceneEl.appendChild(newo);
            $.notify("Se ha enviado la pregunta ", "info");
        }


        conn.onmessage = function(e) {
            var data = JSON.parse(atob(e.data));
            var sceneEl = document.querySelector('a-scene');

            var element = document.getElementById("u-" + data.alias);

            if(data.type == 'portal'){
                console.log("enlazando a " + data.portal);
                var sky=document.getElementById("skybox");
                sky.setAttribute("material","shader: flat; side: double; src: " + data.portal);
                $.notify("Haz sido teletrasportado por " + data.alias, "info");
                console.log('teletransportado');
                return;
            }

            if(data.type == 'question'){
                var sceneEl = document.querySelector('a-scene');
                var newo = document.createElement('a-entity');
                newo.setAttribute('position', data.position);
                newo.setAttribute('text-geometry', 'value:' + data.question + ";");
                //newo.setAttribute('value', data.question);
                if(data.alias == 'admin'){
                    newo.setAttribute('text-geometry', 'value:RESPUESTA:' + data.question + ";");
                    newo.setAttribute('color', '#ECECEC');
                    console.log(newo);
                    $.notify("El administrador respondio una pregunta");
                }else{
                    $.notify(data.alias + " agrego una pregunta");
                }

                sceneEl.appendChild(newo);
                return;
            }

            if(data.type == 'image'){
                var sceneEl = document.querySelector('a-scene');
                var newo = document.createElement('a-image');
                newo.setAttribute('position', data.position);
                newo.setAttribute('src', data.question);
                newo.setAttribute('width', 3);
                newo.setAttribute('height', 3);
                $.notify(data.alias + " agrego una pregunta");

                sceneEl.appendChild(newo);
                return;
            }

            if(data.type == 'video'){
                var sceneEl = document.querySelector('a-scene');
                var newo = document.createElement('a-video');
                newo.setAttribute('position', data.position);
                newo.setAttribute('src', data.question);
                newo.setAttribute('width', 3);
                newo.setAttribute('height', 3);
                newo.setAttribute('autoplay', true);
                newo.setAttribute('loop', true);
                $.notify(data.alias + " agrego una pregunta");

                sceneEl.appendChild(newo);
                return;
            }

            //If it isn't "undefined" and it isn't "null", then it exists.
            if(typeof(element) != 'undefined' && element != null){
                element.setAttribute('position', data.position);
            } else{
                var newo = document.createElement('a-sphere');
                newo.setAttribute('id', 'u-' + data.alias);
                newo.setAttribute('position', data.position);
                newo.setAttribute('color', getRandomColor());
                newo.setAttribute('radius', 0.3);
                sceneEl.appendChild(newo);
            }

        };
        <?php endif; ?>


    </script>

    <div id="openModal" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <h2>Crear pregunta</h2>
            <div>
                <label for="question">Pregunta
                    <textarea id='question' name="question" id="" cols="30" rows="10"></textarea>
                </label>
            </div><br />
            <button id="send-question" onclick="sendQuestion();">Enviar</button>
        </div>
    </div>

    <script>
        $(".close").on('click',function(){
            $(".modalDialog").css({"opacity":"0","pointer-events":"none"});
        });

        $( "body" ).keypress(function(event) {
            if ( event.which == 46 ) {
                $(".modalDialog").css({"opacity":"1","pointer-events":"auto"});
            }
        });
    </script>

</a-scene>
</body>
</html>