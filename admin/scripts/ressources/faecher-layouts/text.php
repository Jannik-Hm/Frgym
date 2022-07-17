<style>
    .grow-wrap {
    /* easy way to plop the elements on top of each other and have them both sized based on the tallest one's height */
    display: grid;
    }
    .grow-wrap::after {
    /* Note the weird space! Needed to preventy jumpy behavior */
    content: attr(data-replicated-value) " ";
    /* This is how textarea text behaves */
    white-space: pre-wrap;
    /* Hidden from view, clicks, and screen readers */
    visibility: hidden;
    }
    .grow-wrap > textarea {
    /* You could leave this, but after a user resizes, then it ruins the auto sizing */
    resize: none;
    /* Firefox shows scrollbar on growth, you can hide like this. */
    overflow: hidden;
    }
    .grow-wrap > textarea,
    .grow-wrap::after {
    /* Identical styling required!! */
    border: 1px solid black;
    padding: 0.5rem;
    font: inherit;
    /* Place on top of each other */
    grid-area: 1 / 1 / 2 / 2;
    }
</style>
<div class="grow-wrap">
    <?php $id=uniqid("text"); ?>
    <textarea name="content1<?php // echo $id ?>" id="<?php echo $id ?>" onInput="this.parentNode.dataset.replicatedValue = this.value" <?php if(!($GLOBALS["edit"])) { echo("style='border: none;background: none;color: #000;' disabled"); } ?>></textarea>
    <input name="contenttype" type="text" value="text" hidden></input>
</div>
<?php
    echo($GLOBALS["id"]);
    echo("edit:".$GLOBALS["edit"]);
?>