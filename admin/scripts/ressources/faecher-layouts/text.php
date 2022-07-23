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
    textarea.normal{
        border: none;background: none;color: #000;
    }
    textarea.edit{}
</style>
<div class="grow-wrap">
    <?php
        require_once realpath($_SERVER["DOCUMENT_ROOT"])."/admin/scripts/admin-scripts.php";
        $result = mysqli_query(getsqlconnection(), "SELECT * FROM faecher WHERE id=\"{$GLOBALS["id"]}\"");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }
    ?>
    <textarea name="content1" id="content1<?php echo $GLOBALS["id"] ?>" class="normal" onInput="this.parentNode.dataset.replicatedValue = this.value" disabled><?php echo $row["content1"]; ?></textarea>
    <input name="contenttype" type="text" value="text" hidden></input>
</div>