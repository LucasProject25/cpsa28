<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="config-manager">
    <div class="container">
        <h1 class="py-4">Gérer mes informations</h1>
        <?php if ($message) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo esc_html($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        <div class="row">
            <h2 class="mb-4">Coordonnées</h2>
            <form method="post">
                <div class="row mb-3">
                    <label for="inputAdresse" class="col-sm-3 col-form-label">Adresse</label>
                    <div class="col-sm-9">
                        <input type="adresse" name="adresse" class="form-control" id="inputAdresse" placeholder="1 Rue de l'Exemple"
                            value="<?php echo $configs['adresse'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCodePostale" class="col-sm-3 col-form-label">Code postal</label>
                    <div class="col-sm-9">
                        <input type="text" name="codePostal" class="form-control" id="inputCodePostale" placeholder="75018 Paris"
                            value="<?php echo $configs['codePostal'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputEmail" class="col-sm-3 col-form-label">Adresse email</label>
                    <div class="col-sm-9">
                        <input type="email" name="email" class="form-control" id="inputEmail" placeholder="exemple@email.com"
                            value="<?php echo $configs['email'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputTel" class="col-sm-3 col-form-label">N° de téléphone</label>
                    <div class="col-sm-9">
                        <input type="tel" name="telephone" class="form-control" id="inputTel"
                            pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}"
                            placeholder="01 01 01 01 01"
                            value="<?php echo $configs['telephone'] ?? ''; ?>">
                    </div>
                </div>
                <h2 class="mb-4">Horaires</h2>
                <div class="row mb-3">
                    <label for="inputJour1" class="col-sm-3 col-form-label">Du</label>
                    <div class="col-sm-9">
                        <input type="input" name="jour1" class="form-control" id="inputJour1" placeholder="Entrez le premier jour de vos horaires"
                            value="<?php echo $configs['jour1'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputJour2" class="col-sm-3 col-form-label">Au</label>
                    <div class="col-sm-9">
                        <input type="input" name="jour2" class="form-control" id="inputJour2" placeholder="Entrez le dernier jour de vos horaires"
                            value="<?php echo $configs['jour2'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputHeureMatin" class="col-sm-3 col-form-label">Matin</label>
                    <div class="col-sm-9">
                        <input type="time" name="matin1" class="form-control" id="inputHeureMatin"
                            value="<?php echo $configs['matin1'] ?? ''; ?>">
                        -
                        <input type="time" name="matin2" class="form-control" id="inputHeureMatin"
                            value="<?php echo $configs['matin2'] ?? ''; ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputHeureAprem" class="col-sm-3 col-form-label">Après-midi</label>
                    <div class="col-sm-9">
                        <input type="time" name="aprem1" class="form-control" id="inputHeureAprem"
                            value="<?php echo $configs['aprem1'] ?? ''; ?>">
                        -
                        <input type="time" name="aprem2" class="form-control" id="inputHeureAprem"
                            value="<?php echo $configs['aprem2'] ?? ''; ?>">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="save_config" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
        <div class="row">

        </div>
    </div>
</div>