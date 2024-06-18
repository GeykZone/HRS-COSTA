<div class="header">

    <style>
        ul {
        list-style: none;
        margin: 0;
        padding:0;
        }

        .notification-drop {
        font-family: 'Ubuntu', sans-serif;
        color: #444;
        
        }
        .notification-drop .item {
        padding: 10px;
        font-size: 18px;
        position: relative;
        border-bottom: 1px solid #ddd;
        }
        .notification-drop .item:hover {
        cursor: pointer;
        }
        .notification-drop .item i {
        margin-left: 10px;
        }
        .notification-drop .item ul {
        display: none;
        position: absolute;
        top: 100%;
        background: #fff;
        left: -440px;
        right: 0;
        border-top: 1px solid #ddd;
        border-radius: 10px;
        z-index: 1000 !important;
        width: 500px;
        overflow: hidden;

        }
        .notification-drop .item ul li {
        font-size: 16px;
        padding: 15px 15px 15px 25px;
        border: solid 1px #19C4B8 ;
        border-radius: 10px;
        margin: 10px;
        background-color: #19C4B8 ;
        color: white;
        }
        .notification-drop .item ul li:hover {
        background: #9CBDE2;
        color: rgba(0, 0, 0, 0.8);
        border: solid 1px #9CBDE2 ;
        }

        @media screen and (min-width: 500px) {
        .notification-drop {
            display: flex;
            justify-content: flex-end;
        }
        .notification-drop .item {
            border: none;
        }
        }

        .notification-bell{
        font-size: 20px;
        }

        .btn__badge {
        background: #FF5D5D;
        color: white;
        font-size: 12px;
        position: absolute;
        top: 0;
        right: 0px;
        padding:  3px 10px;
        border-radius: 50%;
        }

        .pulse-button {
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.5);
        -webkit-animation: pulse 1.5s infinite;
        }

        .pulse-button:hover {
        -webkit-animation: none;
        }

        @-webkit-keyframes pulse {
        0% {
            -moz-transform: scale(0.9);
            -ms-transform: scale(0.9);
            -webkit-transform: scale(0.9);
            transform: scale(0.9);
        }
        70% {
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            -webkit-transform: scale(1);
            transform: scale(1);
            box-shadow: 0 0 0 50px rgba(255, 0, 0, 0);
        }
        100% {
            -moz-transform: scale(0.9);
            -ms-transform: scale(0.9);
            -webkit-transform: scale(0.9);
            transform: scale(0.9);
            box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
        }
        }

        #empty-notif {
            color: #3F3F3FBB;
        }

        .notification-text{
        font-size: 14px;
        font-weight: bold;
        }

        .notification-text span{
        float: right;
        }
    </style>

    <i class="fa-solid fa-bars bar-item"></i>

    <div class="text-logo-container clickable toLandingPage"><img src="./images/logo.png" class="dashboard-logo-small" alt="No Image" /><img src="./images/text-logo.png" class="text-logo" alt="No Image" /></div>

    <div class="profile">
    <ul class="notification-drop">
    <li class="item">
    <i class="fa-regular fa-bell fa-lg notification-bell" aria-hidden="true"></i> <span id="notif_icon" class="btn__badge display-none pulse-button ">0</span>     
    <ul class="notification-container">
        <li id='empty-notif'>Notification box is empty.</li>
    </ul>
    </li>
    </ul>
    <span class="clickable header_icon logout-button" style="width: 10px; margin-right: 15px;"><i class="fa-solid fa-power-off fa-lg"></i></span>
    </div>
</div>