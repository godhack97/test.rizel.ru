<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div>
    <form action="#" method="get">
        <p style="text-align:center">Курс</p>

        <div class="center">
            <label for="COURSE_START">от</label>
            <input value="<?=$arResult['COURSE_START']?>" name="COURSE_START" type="text">
            <label for="COURSE_END">до</label>
            <input value="<?=$arResult['COURSE_END']?>" name="COURSE_END" type="text">
        </div>

        <p style="text-align:center">Дата</p>

        <div class="center">
            <label for="DATE_START">от</label>
            <input value="<?=$arResult['DATE_START']?>" name="DATE_START" type="date">
            <label for="DATE_END">до</label>
            <input value="<?=$arResult['DATE_END']?>" name="DATE_END" type="date">
        </div>

        <p style="text-align:center">Код валюты</p>
        <div class="center">
            <input value="<?=$arResult['CODE']?>" name="CODE" type="text">
        </div>

        <button style="width:100%" type="submit">Фильтровать</button>
    </form>
</div>