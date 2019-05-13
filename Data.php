<?php

$destinations = array(
    array(
        "name" => "Cancun",
        "country" => "Mexico"
    ),
    array(
        "name" => "Punta cana",
        "country" => "Republica Dominicana"
    ),
);

$hotels = array(
    array(
        array(
            "name" => "Secrets Cancun",
            "slug" => "secrets",
            "destination" => 0,
            "logo" => "https://cdn.aframe.io/link-traversal/thumbs/sunrise.png",
            "image" => "pics/secre.jpg",
            "rooms" => array(
                array(
                    "name" => "Suite Jr",
                    "image" => "https://cdn.aframe.io/link-traversal/thumbs/sunrise.png",
                    "photos" => array(
                        array(
                            "name" => "Habitación",
                            "image" => "pics/room1-1.jpg"
                        ),
                        array(
                            "name" => "Hall",
                            "image" => "pics/room1-2.jpg"
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        array(
            "name" => "Dreams Punta Cana",
            "slug" => "dreams",
            "destination" => 0,
            "logo" => "https://cdn.aframe.io/link-traversal/thumbs/sunrise.png",
            "image" => "pics/punta.jpg",
            "rooms" => array(
                array(
                    "name" => "Suite Jr Preferred",
                    "image" => "https://cdn.aframe.io/link-traversal/thumbs/sunrise.png",
                    "photos" => array(
                        array(
                            "name" => "Habitación",
                            "image" => "pics/room2-1.jpg"
                        ),
                        array(
                            "name" => "Hall",
                            "image" => "pics/room2-2.jpg"
                        ),
                    )
                ),
            )
        ),
    ),
);