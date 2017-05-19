$(document).ready(function(){

    $(".startDockerMigration").on("click", function(){
      var companyId = $(this).data("company-id");

       var r = new XMLHttpRequest();
       r.open("POST", "../sys/startDockerMigration.php", true);
       r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

       r.onreadystatechange = function () {
           if (r.readyState !== 4 || r.status !== 200) return;

           var successWrapper = $("#successWrapper");
           var response = r.responseText;

           var content = "<strong>Holy guacamole!</strong> You started the Docker Migration.<br/><br/>";
           content += response.replace("\n", "<br/>");

           $(".contentWrapper", successWrapper).html(content);

           successWrapper.addClass("show");

           setTimeout(function(){
               successWrapper.removeClass("show");
           }, 5000);

       };

       r.send("companyId="+companyId);

   });

});