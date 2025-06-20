<style>
    .fotnav{
        background-color: (248,245, 233, 1.0);
        backdrop-filter: blur(10px);
        
    }
    .fotnav-container{
        background-color: #644A31;
        border-radius: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20%;
        padding: 10px;
        opacity: 1;
    }
    .logo{
        width: auto;
        height: 80%;
    }
    /* @media (max-width: 850px){
        .fotnav-container{
            gap: 50px;
        }
    } */
</style>
<footer class="w-100 position-fixed bottom-0 fotnav py-3 px-5" style="z-index: 3;">
    <div class="w-100 h-100 rounded-4 fotnav-container">
        <a href="home.php">
            <img src="./assets/home.svg" alt="" width="70px" height="70px" class="logo">
        </a>
        <a href="upload-artwork.php">
            <img src="./assets/upload.svg" alt="" width="70px" height="70px">
        </a>
        <a href="profile.php">
            <img src="./assets/profile-logo.svg" alt="" width="70px" height="70px">
        </a>
    </div>
</footer>