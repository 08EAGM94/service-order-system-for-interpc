<main class="editSign-main">
    <?php if(!empty($_SESSION["isAdmin"])):?>
        <div class="searchForm-wrapper">
        <form class="searchForm" action="<?= base_url; ?>home/?homeController=user&homeAction=editSign" method="POST">
            <div class="searchForm__select-wrapper">
                <select class="js-example-placeholder-single" name="usuarios" id="usersSelect">
                    <option></option>
                    <?php if (sizeof($users) > 0): ?>
                        <?php foreach ($users as $usr): ?>
                            <option value="<?= $usr["Id"] ?>"><?= $usr["Nombre"].' '.$usr["Apellidos"]?> - <?= $usr["Alias"] ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>      
                </select>
            </div>    
            <input class="userform__submit without-marginTop" type="submit" value="Buscar"/>
        </form>
        </div>
        <?php if(!empty($_SESSION["userSign_userId"])):?>
        <?php if(!empty($user_info["Firma"])):?>
        <form class="editSign-form" action="<?= base_url;?>finishing/?controller=form&action=techsign" method="POST">
            <div class="edit-sign-fieldset__wrapper">
                <fieldset class="sign-column__fieldset less-width">
                            <legend class="sign-column__legend">TÉCNICO</legend>
                            <div class="edit-sign__sign-img-wrapper">
                            <img src="<?= base_url.'finishing/uploads/firmas/'.$user_info["Firma"];?>?nocache=<?= time();?>" style="width: 80%; height: 100%;"/>
                            </div>
                            <div class="sign-column__tech-sign">
                                <?= $user_info["Nombre"] . ' ' . $user_info["Apellidos"]; ?>
                            </div>
                </fieldset>
                <div class="binnacle-form__main-button-wrapper put-center">
                    <input class="binnacle-form__submit" type="submit" value="Editar firma"/>
                </div>
            </div>
            
            <input type="hidden" name="userId" value="<?=$user_info["Id"]?>"/>
            <input type="hidden" name="userName" value="<?=$user_info["Nombre"]?>"/>
            <input type="hidden" name="userSurname" value="<?=$user_info["Apellidos"]?>"/>
            <input type="hidden" name="oldTechSign" value="<?=$user_info["Firma"]?>"/>
            
        </form>    
        <?php else:?>
        <div class="without-rows__message">
           <h1>Este usuario aún no ha generado su firma...</h1>
        </div>
        <?php endif;?>
        <?php endif;?>
    <?php endif;?>
    
    <?php if($_SESSION["identity"]["Privilegio"] === "user"):?>
    
    <?php if(!empty($_SESSION["identity"]["Firma"])):?>
    <form class="editSign-form add-more-height" action="<?= base_url;?>finishing/?controller=form&action=techsign" method="POST">
        <div class="edit-sign-fieldset__wrapper">
            <fieldset class="sign-column__fieldset">
                        <legend class="sign-column__legend">TÉCNICO</legend>
                        <div class="edit-sign__sign-img-wrapper">
                        <img src="<?= base_url.'finishing/uploads/firmas/'.$_SESSION["identity"]["Firma"];?>?nocache=<?= time();?>" style="width: 80%; height: 100%;"/>
                        </div>
                        <div class="sign-column__tech-sign">
                            <?= $_SESSION["identity"]["Nombre"] . ' ' . $_SESSION["identity"]["Apellidos"]; ?>
                        </div>
            </fieldset>
            <div class="binnacle-form__main-button-wrapper">
                <a class="binnacle-form__button-link" href="<?= base_url;?>home/">Regresar</a>
                <input class="binnacle-form__submit" type="submit" value="Editar firma"/>
            </div>
        </div>
        
        <input type="hidden" name="userId" value="<?=$_SESSION["identity"]["Id"]?>"/>
        <input type="hidden" name="userName" value="<?=$_SESSION["identity"]["Nombre"]?>"/>
        <input type="hidden" name="userSurname" value="<?=$_SESSION["identity"]["Apellidos"]?>"/>
        <input type="hidden" name="oldTechSign" value="<?=$_SESSION["identity"]["Firma"]?>"/>
    </form>
    <?php else:?>
    <div class="without-rows__message add-more-height">
         <h1>Aún no tienes una firma, puedes generarla en la conformidad de actividades de una bitácora...</h1>
         <div class="binnacle-form__main-button-wrapper user-return">
                <a class="numkey-box__return-link increse-width" href="<?= base_url; ?>home/">Regresar</a>
         </div>
    </div>
    <?php endif;?>
    
    <?php endif;?>
    
</main>
<?php Utils::unsetFormSessions();?>
