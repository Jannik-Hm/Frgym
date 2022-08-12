<style>
    .one-segment-title {
        width: fit-content;
        text-align: center;
        font-size: 25px;
        border-bottom: 2px solid #3d869c;
        margin: 0 auto;
    }

    .one-segment-div {
        display: inline;
        padding: 0vw 15vw;
        width: 70vw;
    }

    .one-segment-text {
        text-align: justify;
        max-width: 70vw;
        margin: 0 auto;
    }

    /* .one-segment-text::first-letter {
        font-size: 50px;
        position: relative;
        top: 25px
    } */

    .one-segment-fig:first-of-type {
        text-align: center;
        border-radius: 10px;
        border: 1px solid #3d869c;
        right: 30vw;
        float: right;
        margin: 40px 15vw 10px 10px;
        height: fit-content;
        width: fit-content;
    }

    .one-segment-img:first-of-type {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: none;
        max-width: 25vw;
    }

    .one-segment-imgcap:first-of-type {
        border-top: 1px solid #3d869c;
        padding: 10px;
        max-width: 25vw;
        bottom: 0px;
        text-overflow: clip;
        text-align: center;
    }

    .one-segment-fig:last-of-type {
        text-align: center;
        border-radius: 10px;
        border: 1px solid #3d869c;
        left: 15vw;
        float: left;
        margin: 40px 10px 10px 15vw;
        height: fit-content;
        width: fit-content;
    }

    .one-segment-img:last-of-type {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: none;
        max-width: 25vw;
    }

    .one-segment-imgcap:last-of-type {
        border-top: 1px solid #3d869c;
        padding: 10px;
        max-width: 25vw;
        bottom: 0px;
        text-overflow: clip;
        text-align: center;
    }
</style>
<div>
    <h1 class="one-segment-title"><?php echo (isset($_GET['title']) ? $_GET['title'] : 'test'); ?></h1>
    <div class="one-segment-div">
        <figure class="one-segment-fig">
            <img class="one-segment-img" src=<?php echo ('"' . (isset($_GET['imgsrc2']) ? $_GET['imgsrc2'] : 'https://via.placeholder.com/200?text=Placeholder') . '"'); ?> alt=<?php echo ('"' . (isset($_GET['imgalt2']) ? $_GET['imgalt2'] : '') . '"'); ?>>
            <figcaption class="one-segment-imgcap"><?php echo (isset($_GET['imgcap2']) ? $_GET['imgcap2'] : ''); ?></figcaption>
        </figure>
        <figure class="one-segment-fig">
            <img class="one-segment-img" src=<?php echo ('"' . (isset($_GET['imgsrc1']) ? $_GET['imgsrc1'] : 'https://via.placeholder.com/200?text=Placeholder') . '"'); ?> alt=<?php echo ('"' . (isset($_GET['imgalt1']) ? $_GET['imgalt1'] : '') . '"'); ?>>
            <figcaption class="one-segment-imgcap"><?php echo (isset($_GET['imgcap1']) ? $_GET['imgcap1'] : ''); ?></figcaption>
        </figure>
        <p class="one-segment-text"><?php echo (isset($_GET['txt']) ? $_GET['txt'] : "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. ") ?></p>
    </div>
</div>


<!-- 

title=Informatik%20Automaten&imgalt1=1terAutomatAlt&imgcap1=1terAutomat$imgalt2=2terAutomatAlt&imgcap2=2terAutomat

 -->