function showResults(str) {
			    if (str.length == 0) {
			        document.getElementById("searchResults").innerHTML = "";
			        return;
			    } else {
			    	var xmlhttp = new XMLHttpRequest();
			        xmlhttp.onreadystatechange = function() {
			            if (this.readyState == 4 && this.status == 200) {
			                document.getElementById("searchResults").innerHTML = this.responseText;
			            }
			        };
			        xmlhttp.open("GET", "getsearch.php?search=" + str, true);
			        xmlhttp.send();
			    }
			}