$(document).ready(function() {	
    $(document).on("click", "#inhibit-user", function () {
		var row = $(this).closest("tr");  
        var user = row.find("#user").text();
        var userid = row.find("#userid").text();
		var teamid = row.find("#teamid").text();
		var restrictions = row.find("#restrictions").text();
        if (restrictions.indexOf("Parts") !== -1) {
            document.getElementById('parts-checkbox').checked = true;
        }
        else {
            document.getElementById('parts-checkbox').checked = false;
        }
        if (restrictions.indexOf("Safety") !== -1) {
            document.getElementById('safety-checkbox').checked = true;
        }
        else {
            document.getElementById('safety-checkbox').checked = false;
        }
        if (restrictions.indexOf("Private") !== -1) {
            document.getElementById('private-checkbox').checked = true;
        }
        else {
            document.getElementById('private-checkbox').checked = false;
        }
        document.getElementById('getuser').innerHTML = user;
		document.getElementById('getteamid').innerHTML = teamid;
		document.getElementById('inhibit-userid').value = userid;
	});
	$(document).on("click", "#delete-user", function() {
		var row = $(this).closest("tr");  
        var user = row.find("#user").text();
        var userid = row.find("#userid").text();
		var teamid = row.find("#teamid").text(); 
        document.getElementById('getuser2').innerHTML = user;
		document.getElementById('getteamid2').innerHTML = teamid;
		document.getElementById('delete-userid').value = userid;
	});

    $('.btn-teaminfo').on('click', function(){
        var id = this.id;
        $('.teams').css('table-layout', 'auto');
        if($('#info-' + id).css('display') == "none"){
            $('.user-info').css('display', 'none');
            $('#info-' + id).css('display', 'table-row');
            $('#userinfo-' + id).css('display', 'block');
        }
        else {
            $('#info-' + id).css('display', "none");
        }
        
    });
});

if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
    document.getElementById('back-btn').classList.add('back-btn-minimized');
    document.getElementById('back-btn-text').style.display = 'none';
}
$(window).resize(function () {
    if (window.innerWidth <= 500 && document.getElementById('back-btn-text').style.display != 'none') {
        document.getElementById('back-btn').classList.add('back-btn-minimized');
        document.getElementById('back-btn-text').style.display = 'none';
    }
    else if (window.innerWidth > 500 && document.getElementById('back-btn-text').style.display == 'none') {
        document.getElementById('back-btn').classList.remove('back-btn-minimized');
        document.getElementById('back-btn-text').style.display = 'initial';
    }
})