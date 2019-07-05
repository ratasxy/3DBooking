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
    <link rel="stylesheet" href="css/materialize.min.css">
    <script src="js/materialize.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/notify.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <style>
        #escena{
            height: 600px;
        }
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

<div class="container">
    <div class="row">
        <div id="escena" class="col s8 blue darken-4 white-text center-align principal">
<a-scene embedded background="color: #ECECEC">
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

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        var conn = new WebSocket('ws://127.0.0.1:2222');
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

            if($("#isImage").is(':checked'))
            {
                sendImage();
                return;
            }

            if($("#isVideo").is(':checked'))
            {
                sendVideo();
                return;
            }

            value = $('#question').val();
            position = $('#question').attr('data-position');
            console.log(position);

            $('#question').val('');
            $('#question').attr('data-position', '');

            var sceneEl = document.querySelector('a-scene');
            var camera = sceneEl.querySelector('a-camera')

            position = JSON.parse(position);
            position.y = position.y - 0.5;

            conn.send(btoa(JSON.stringify({type:"question", room:"<?echo $_GET['sala']; ?>", alias:"admin", position:position, question:value})));

            var newo = document.createElement('a-text');
            newo.setAttribute('position', position);
            newo.setAttribute('value', value);
            sceneEl.appendChild(newo);
            $.notify("Se ha enviado una respuesta ", "info");
        }

        function sendImage(){

            value = $('#question').val();
            position = $('#question').attr('data-position');

            $('#question').val('');
            $('#question').attr('data-position', '');
            $( "#isImage" ).prop( "checked", false );

            var sceneEl = document.querySelector('a-scene');
            var camera = sceneEl.querySelector('a-camera')

            position = JSON.parse(position);
            position.y = position.y - 3;

            conn.send(btoa(JSON.stringify({type:"image", room:"<?echo $_GET['sala']; ?>", alias:"admin", position:position, question:value})));

            var newo = document.createElement('a-image');

            newo.setAttribute('position', position);
            newo.setAttribute('src', value);
            newo.setAttribute('width', 3);
            newo.setAttribute('height', 3);
            sceneEl.appendChild(newo);
            $.notify("Se ha enviado una imagen ", "info");
        }

        function sendVideo(){

            value = $('#question').val();
            position = $('#question').attr('data-position');

            $('#question').val('');
            $('#question').attr('data-position', '');
            $( "#isImage" ).prop( "checked", false );

            var sceneEl = document.querySelector('a-scene');
            var camera = sceneEl.querySelector('a-camera')

            position = JSON.parse(position);
            position.y = position.y - 3;

            conn.send(btoa(JSON.stringify({type:"video", room:"<?echo $_GET['sala']; ?>", alias:"admin", position:position, question:value})));

            var newo = document.createElement('a-video');

            newo.setAttribute('position', position);
            newo.setAttribute('src', value);
            newo.setAttribute('width', 3);
            newo.setAttribute('height', 3);
            sceneEl.appendChild(newo);
            $.notify("Se ha enviado una imagen ", "info");
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
                console.log(data);
                var sceneEl = document.querySelector('a-scene');
                var histo = document.querySelector('#histo');
                var q = document.querySelector('#question');
                var newo = document.createElement('a-text');

                if(data.alias != 'admin'){
                    var newl = document.createElement('li');
                    newl.classList.add('collection-item');
                    newl.append(data.question);
                    newl.addEventListener("click", function (event) {
                        $(".modalDialog").css({"opacity":"1","pointer-events":"auto"});
                        q.setAttribute('data-position', event.target.getAttribute('position'))
                    });
                    newl.setAttribute('position', JSON.stringify(data.position));
                    histo.appendChild(newl);
                }


                newo.setAttribute('position', data.position);
                newo.setAttribute('value', data.question);
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


    </script>

    <div id="openModal" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <h2>Crear pregunta</h2>
            <style>
                #isImage {
                    -webkit-appearance:checkbox;!important;
                    opacity: inherit;!important;
                    pointer-events: all;!important;
                }
                #isVideo {
                    -webkit-appearance:checkbox;!important;
                    opacity: inherit;!important;
                    pointer-events: all;!important;
                }
            </style>
            <div>
                <label for="isImage">Es una imagen
                    <input name="isImage" type="checkbox" id="isImage"/>
                    <br/>
                </label>
                <label for="isVideo">Es una video
                    <input name="isVideo" type="checkbox" id="isVideo"/>
                    <br/>
                </label>
                <label for="question">Pregunta
                    <textarea id='question' name="question" data-position="" id="" cols="30" rows="10"></textarea>
                </label>
            </div><br />
            <button id="send-question" onclick="sendQuestion();">Enviar</button>
        </div>
    </div>

    <script>
        $(".close").on('click',function(){
            $(".modalDialog").css({"opacity":"0","pointer-events":"none"});
        });

    </script>

</a-scene>
        </div>
        <div class="col s4 blue lighten-5 principal">
            <ul id="histo" class="collection">

            </ul>
        </div>
    </div>
</div>
</body>
</html>