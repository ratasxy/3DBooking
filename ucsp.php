<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>WebVR - Aframe - VR walkthrough</title>
    <meta name="description" content="UCSP Biblioteca">
    <meta name="author" content="Juan Alfredo Salas Santillana">
    <script src="js/aframe.js"></script>
    <script src="js/aframe-animation.js"></script>
    <script src="js/aframe-look.js"></script>
    <script src="js/shader.js"></script>
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
        AFRAME.registerComponent('infobar',{
            init:function(){
                this.el.addEventListener('click',function(){
                    //set the skybox source to the new image as per the spot
                    var sky=document.getElementById("skybox");
                    sky.setAttribute("src",data.linkto);

                    var spotcomp=document.getElementById("spots");
                    var currspots=this.parentElement.getAttribute("id");
                    //create event for spots component to change the spots data
                    spotcomp.emit('reloadspots',{newspots:data.spotgroup,currspots:currspots});
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
                    //set the skybox source to the new image as per the spot
                    var sky=document.getElementById("skybox");
                    sky.setAttribute("src",data.linkto);

                    var spotcomp=document.getElementById("spots");
                    var currspots=this.parentElement.getAttribute("id");
                    //create event for spots component to change the spots data
                    spotcomp.emit('reloadspots',{newspots:data.spotgroup,currspots:currspots});
                });
            }
        });
        AFRAME.registerComponent('info',{
            schema:{
                linkto:{type:"string",default:""},
            },
            init:function(){

                //add image source of hotspot icon
                this.el.setAttribute("src","#info");
                //make the icon look at the camera all the time
                this.el.setAttribute("look-at","#cam");

                var data=this.data;

                this.el.addEventListener('click',function(){
                    console.log(data.linkto);
                    var cube=document.getElementById(data.linkto);

                    if(cube.getAttribute("scale").x == 1){
                        cube.setAttribute("scale", "0 0 0")
                    }else{
                        cube.setAttribute("scale", "1 1 1")
                    }

                });
            }
        });
    </script>
</head>
<body>
<a-scene background="color: #ECECEC">
    <a-assets>
        <img id="point1" src="ucsp/360_0044.jpg"/>
        <img id="point2" src="ucsp/360_0042.jpg"/>
        <img id="point3" src="ucsp/360_0039.jpg"/>

        <img id="hotspot" src="img/hotspot.png"/>
        <img id="info" src="img/info.png"/>
    </a-assets>

    <a-entity id="spots" hotspots>
        <a-entity id="group-point1">
            <a-image spot="linkto:#point2;spotgroup:group-point2" position="9 1.6 4"></a-image>
            <a-video src="ucsp/video.mp4" width="4" height="3" rotation="0 200 0" position="5 0 8" loop="false"></a-video>
        </a-entity>
        <a-entity id="group-point2" scale="0 0 0">
            <a-image spot="linkto:#point1;spotgroup:group-point1" position="-15 1.6 -1"></a-image>
            <a-image spot="linkto:#point3;spotgroup:group-point3" position="13 1.6 1"></a-image>
            <a-image info="linkto:info-bib;" position="-8 1.6 12"></a-image>
            <a-entity id="info-bib" geometry="primitive: box" position="-2.5 1.6 2" rotation="0 30 0" scale="0 0 0" material="shader: html; target: #htmlElement"></a-entity>
        </a-entity>
        <a-entity id="group-point3" scale="0 0 0">
            <a-image spot="linkto:#point2;spotgroup:group-point2" position="-8 1.6 12"></a-image>
        </a-entity>
    </a-entity>



    <a-sky id="skybox" src="#point1">

    </a-sky>

    <a-entity id="cam" camera position="0 1.6 0" look-controls>
        <a-entity cursor="fuse:true;fuseTimeout:1000"
                  geometry="primitive:ring;radiusInner:0.01;radiusOuter:0.02"
                  position="0 0 -1.8"
                  material="shader:flat;color:#ff0000"
                  animation__mouseenter="property:scale;to:3 3 3;startEvents:mouseenter;endEvents:mouseleave;dir:reverse;dur:2000;loop:1">
        </a-entity>
    </a-entity>

</a-scene>

<div id="htmls">
    <div id="htmlElement" style="
        width: 300px; height: 300px; position: fixed;
        left: 0; top: 0; z-index: -1; overflow: hidden;
        padding: 20px;
        border: 5px solid rebeccapurple;
        background: #F8F8F8;">
        <div style="">
            <h2>Biblioteca</h2>
            <div  style="color: #333; font-size: 12px">La biblioteca Victor Andres Belaunde cuenta con X libros</div>
        </div>
    </div>
</div>
</body>
</html>