<style>
    .img-profile{
        border-radius: 50%;
        width: 80px;
        height: 80px;
        object-fit: cover;
    }
    .content-side{
        height: 80px;
        width: fit-content;
        background-color: #FDFDFD;
        border-radius: 50px;
        padding-right: 20px;
    }
    img-logo{
        height: 100px;
        width: 240px;
    }
    @media (max-width: 600px){
        .img-profile{
            width: 40px;
            height: 40px;
        }
        .content-side{
            height: 40px;
        }
        .img-logo{
            height: 60px;
            width: 100px;
        }
    }
</style>
<section class="px-5 py-2 d-flex justify-content-between align-items-center flex-row w-100">
    <img src="./assets/varta-logo.svg" alt="" class="img-logo">
    <div class="content-side d-flex gap-3 align-items-center">
        <img src="?image=<?php echo $data['user_id']?>" alt="" class="img-profile">
        <h2 class="m-0"><?php echo $data['username']?></h2>
    </div>
</section>