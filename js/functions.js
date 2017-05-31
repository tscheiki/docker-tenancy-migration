$(document).ready(function () {

    var migrationBtn = $(".migrationBtn");
    var startContent = "Start Docker Migration";
    var stopContent = "Stop Container";

    var pathToTransactionFile = "";
    var startTransactionText = "";
    var successText = "";

    var startContainerClass = "startContainer";
    var stopContainerClass = "stopContainer";

    // Check container states
    migrationBtn.each(function () {
        var companyId = $(this).data("company-id");
        if (localStorage.getItem(companyId) === null || localStorage.getItem(companyId) === "false") {
            $(this).text(startContent).removeClass(stopContainerClass).addClass(startContainerClass);
        } else {
            $(this).text(stopContent).removeClass(startContainerClass).addClass(stopContainerClass);
        }
    });

    migrationBtn.on("click", function () {
        var startDockerMigrationBtn = $(this);
        var companyId = startDockerMigrationBtn.data("company-id");
        var domain = "http://c00" + companyId + ".r2o";

        if(startDockerMigrationBtn.hasClass(startContainerClass)){

            pathToTransactionFile = "../migration/createMigration.php";
            startTransactionText = "<strong>Holy guacamole!</strong> You started the Docker Migration. I'll inform you when everything is migrated.";
            successText = "<strong>Oh yeah!</strong> Everything successfully migrated. Take a look here: <a target='_blank' href='" + domain + "'>" + domain + "</a>";

        } else if (startDockerMigrationBtn.hasClass(stopContainerClass)){

            pathToTransactionFile = "../migration/stopContainer.php";
            startTransactionText = "<strong>Holy guacamole!</strong> Your container will be gone in a few seconds.";
            successText = "<strong>Oh yeah!</strong> Container stopped. Take a look here: <a target='_blank' href='" + domain + "'>" + domain + "</a>";

        }

        // Show spinner
        startDockerMigrationBtn.html("<i class='fa fa-spin fa-spinner'></i>");

        // Show funny text on top
        var successWrapper = $("#successWrapper");
        var successWrapperContent = $(".contentWrapper", successWrapper);

        successWrapperContent.html(startTransactionText);
        successWrapper.addClass("show");

        var r = new XMLHttpRequest();
        r.open("POST", pathToTransactionFile, true);
        r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        r.onreadystatechange = function () {
            if (r.readyState !== 4 || r.status !== 200) return;

            setTimeout(function () {

                successWrapperContent.html(successText);

                if(startDockerMigrationBtn.hasClass(startContainerClass)){

                    localStorage.setItem(companyId, true);
                    startDockerMigrationBtn.text(stopContent).removeClass(startContainerClass).addClass(stopContainerClass);

                } else if (startDockerMigrationBtn.hasClass(stopContainerClass)){

                    localStorage.setItem(companyId, false);
                    startDockerMigrationBtn.text(startContent).removeClass(stopContainerClass).addClass(startContainerClass);

                }

            }, 10000);
        };

        r.send("companyId=" + companyId);

    });

});