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

    .one-segment-fig {
        text-align: center;
        border-radius: 10px;
        border: 1px solid #3d869c;
        right: 15vw;
        float: right;
        margin: 40px 15vw 10px 10px;
        height: fit-content;
        width: fit-content;
    }

    .one-segment-img {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        border-bottom: none;
        max-width: 25vw;
    }

    .one-segment-imgcap {
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
            <img class="one-segment-img" src=<?php echo ('"' . (isset($_GET['imgsrc']) ? $_GET['imgsrc'] : 'https://via.placeholder.com/200?text=Placeholder') . '"'); ?> alt=<?php echo ('"' . (isset($_GET['imgalt']) ? $_GET['imgalt'] : '') . '"'); ?>>
            <figcaption class="one-segment-imgcap"><?php echo (isset($_GET['imgcap']) ? $_GET['imgcap'] : ''); ?></figcaption>
        </figure>
        <p class="one-segment-text"><?php echo (isset($_GET['txt']) ? $_GET['txt'] : "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse, nostrum autem reiciendis quidem quaerat in non laboriosam distinctio officia debitis similique laudantium ducimus doloribus numquam natus error minus eos cupiditate. ") ?></p>
    </div>
</div>