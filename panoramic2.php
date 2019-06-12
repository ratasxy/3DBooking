<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WebVR - Aframe - VR walkthrough</title>
    <meta name="description" content="WebVR - Aframe - VR walkthrough">
    <meta name="author" content="Kumar Ahir - VR Designer">
    <script src="js/aframe.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/annyang/2.6.0/annyang.min.js"></script>
    <script src="https://unpkg.com/aframe-animation-component@^4.1.2/dist/aframe-animation-component.min.js"></script>
    <script src="https://unpkg.com/aframe-look-at-component@0.5.1/dist/aframe-look-at-component.min.js"></script>
    <script src="https://rawgit.com/urish/aframe-camera-events/master/index.js"></script>
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

    <a-entity id="skybox" src="#point0" geometry="primitive: sphere; radius: 20;" material="shader: flat; side: double; src: #point0">

    </a-entity>

    <a-camera id="cam" position-listener>
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
                'atras': function() {
                    window.location.href = "hotel.php?city=<?php echo $_GET['city'];?>&hotel=<?php echo $_GET['hotel'];?>";
                },
            };

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

        var conn = new WebSocket('ws://0.0.0.0:8080');
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

        conn.onmessage = function(e) {
            console.log('llego mensaje');
            var data = JSON.parse(atob(e.data));
            var sceneEl = document.querySelector('a-scene');

            var element = document.getElementById("u-" + data.alias);

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

</a-scene>
</body>
</html>