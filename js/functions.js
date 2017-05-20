$(document).ready(function () {

    $(".startDockerMigration").on("click", function () {
        var startDockerMigrationBtn = $(this);
        var companyId = startDockerMigrationBtn.data("company-id");

        // Show spinner
        startDockerMigrationBtn.html("<i class='fa fa-spin fa-spinner'></i>");

        // Show funny text on top
        var successWrapper = $("#successWrapper");
        var successWrapperContent = $(".contentWrapper", successWrapper);

        successWrapperContent.html("<strong>Holy guacamole!</strong> You started the Docker Migration. I'll inform you when everything is migrated.");
        successWrapper.addClass("show");

        var r = new XMLHttpRequest();
        r.open("POST", "../migration/createMigration.php", true);
        r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        r.onreadystatechange = function () {
            if (r.readyState !== 4 || r.status !== 200) return;

            var response = r.responseText;

            setTimeout(function(){

                var newDomain = "http://c00"+companyId+".r2o";
                var successContent = "<strong>Oh yeah!</strong> ";
                successContent += "Everything successfully migrated. Take a look here: <a target='_blank' href='"+newDomain+"'>"+newDomain+"</a>";

                successWrapperContent.html(successContent);
                startDockerMigrationBtn.html("Successfully migrated");

            }, 10000);
        };

        r.send("companyId=" + companyId);

    });

});