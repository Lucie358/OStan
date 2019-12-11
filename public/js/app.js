var app = {
	// On récupère l'url de base du server grâce à window.location
	baseUrl: window.location.origin,
	handleStatusButtonAd: function(elementButton, postId, statusCode) {
		// console.log(postId);
		// console.log(window.location)
		// console.log(baseUrl);
		// console.log(elementButton);
		// On utilise ajax pour faire une requête http en mode "rest" donc sans render
		$.ajax({
			method: "PATCH",
			url: app.baseUrl + "/backend/" + postId + "/status/" + statusCode,
			// En cas de succès on affiche un toast confirmant le changement de rôle du user.
			success: function(result) {
				toastr.success("Statut mis à jour");
				elementButton.textContent =
					statusCode == "BLOCKED"
						? "Débloquer l'annonce"
						: "Bloquer l'annonce";
				elementButton.onclick = function() {
					return handleStatusButtonAd(
						elementButton,
						postId,
						statusCode == "BLOCKED" ? "UNBLOCKED" : "BLOCKED"
					);
				};
			},
			error: function() {
				toastr.error("Impossible de mettre à jour le statut.");
			}
		});
	},
	
	handleRoleSelect: function(element, userId) {
		// console.log(element.value); A décommenter si besoin
		// console.log(userId);
		// console.log(window.location)
		// console.log(baseUrl);
		// On utilise ajax pour faire une requête http en mode "rest" donc sans render
		$.ajax({
			method: "PATCH",
			url: app.baseUrl + "/backend/user/" + userId + "/role/" + element.value,
			// En cas de succès on affiche un toast confirmant le changement de rôle du user.
			success: function(result) {
				toastr.success(
					"Le rôle de " + result.firstname + " a été mis à jour"
				);
			},
			error: function() {
				toastr.error(
					"Impossible de mettre à jour le rôle, veuillez réessayer."
				);
			}
		});
	},
	handleStatusButtonComment: function(elementButton, commentId, statusCode) {
		// console.log(postId);
		// console.log(window.location)
		// console.log(baseUrl);
		// console.log(elementButton);
		// On utilise ajax pour faire une requête http en mode "rest" donc sans render
		$.ajax({
			method: "PATCH",
			url:
				app.baseUrl +
				"/backend/comment/" +
				commentId +
				"/status/" +
				statusCode,
			// En cas de succès on affiche un toast confirmant le changement de rôle du user.
			success: function(result) {
				console.log(toastr);
				toastr.success("Statut mis à jour");
				elementButton.textContent =
					statusCode == "BLOCKED"
						? "Débloquer le commentaire"
						: "Bloquer le commentaire";
				elementButton.onclick = function() {
					return handleStatusButtonComment(
						elementButton,
						commentId,
						statusCode == "BLOCKED" ? "UNBLOCKED" : "BLOCKED"
					);
				};
			},
			error: function() {
				toastr.error("Impossible de mettre à jour le statut.");
			}
		});
	},
	// Element = Element html du select que l'on a grâce au 'this' dans le onchange du select.
	// PostId = l'id de l'user envoyé en 2ème paramètre grâce à Twig
	handleStatusButton: function(elementButton, postId, statusCode) {
		// On utilise ajax pour faire une requête http en mode "rest" donc sans render
		$.ajax({
			method: "PATCH",
			url: app.baseUrl + "/backend/" + postId + "/status/" + statusCode,
			// En cas de succès on affiche un toast confirmant le changement de rôle du user.
			success: function(result) {
				toastr.success("Statut mis à jour");
				elementButton.textContent =
					statusCode == "BLOCKED"
						? "Débloquer l'annonce"
						: "Bloquer l'annonce";
				elementButton.onclick = function() {
					return handleStatusButton(
						elementButton,
						postId,
						statusCode == "BLOCKED" ? "UNBLOCKED" : "BLOCKED"
					);
				};
			},
			error: function() {
				toastr.error("Impossible de mettre à jour le statut.");
			}
		});
	},
	// C'est la fonction qui est appelé quand le user coche ou décoche editeur. (isEditor est = true ou false)
	_toggleEditorFields: function(isEditor) {
		// Les deux champs que l'on souhaite ajouter quand on coche editeur
		const siretField = document.getElementById("registration_form_siret");
		const companyField = document.getElementById(
			"registration_form_companyname"
		);
		const companyFieldHelp = document.getElementById(
			"registration_form_companyname_help"
		);

		// (isEditor est sois true, sois false (c'est le event.target.checked plus haut))
		if (isEditor) {
			// Si isEditor est true alors on affiche les deux inputs et on les mets en required
			siretField.style.display = "block";
			siretField.required = true;

			companyField.style.display = "block";
			companyFieldHelp.style.display = "block";
			companyField.required = true;
		} else {
			// Si isEditor est false alors on n'affiche pas les deux inputs et on les mets en non required et on les vide
			siretField.style.display = "none";
			siretField.value = "";
			siretField.required = false;

			companyField.style.display = "none";
			companyFieldHelp.style.display = "none";
			companyField.value = "";
			companyField.required = false;
		}
	},
	setValue: function() {
		$("#editor1").val($("input#val").val());
	},
	 
};

$(document).ready(function() {
	$(".lightbox img").click(function() {
		var $body = $("body");
		var $imgHref = $(this).attr("src");
		var $lightbox = $('<div id="lightbox">');
		var $lightboxImage = $("<img>").attr("src", $imgHref);
		$lightbox.append($lightboxImage);
		$lightbox.fadeIn(400);
		$body.append($lightbox);
		$("#lightbox").on("click", function(remove) {
			//Lorsque l'utilisateur clique en dehors de l'image, la lightbox se ferme et est supprimée
			if (remove.target == this) {
				//La fermeture au clique ne fonctionne qu'en dehors de l'image
				$lightbox.fadeOut(200, function() {
					$("#lightbox").remove();
				});
			}
		});
	});
});

$(document).ready(function() {
	// Je séléctionne le champ jobs du form par son ID
	// Je récupère ses enfants avec children (les divs qui contiennent label et input par job)
	// Je boucle sur chacun d'entre eux (index cest le numéro de l'enfant dans le tableau (1ere div, 2eme div...) et element c'est la div en question)
	$("#registration_form_jobs")
		.children()
		.each(function(index, element) {
			// dans l'element, donc la div que je parcours, je cherche un label et je récupère son texte
			const label = $(element)
				.find("label")
				.text();
			// Normalize est là pour remplacer les caractères spéciaux si y'en a
			const labelNormalized = label
				.normalize("NFD")
				.replace(/[\u0300-\u036f]/g, "")
				.toUpperCase();
			// console.log(labelNormalized);
			// Si le texte du label est EDITEUR alors je cherche dans l'element (la div) un input, et lorsque l'input "change" (coché/décoché) j'appelle
			// une fonction toggleEditorField.

			if (labelNormalized === "EDITEUR") {
				$(element)
					.find("input")
					.on("change", function(event) {
						// event.target.checked c'est la veuleur de la checkbox (booléenne = true ou false) qui sera envoyé dans la fonction
						app._toggleEditorFields(event.target.checked);
					});
			}
		});

	// On appel à la main la fonction avec la valeur false en dur pour cacher les inputs par défaut au chargement
	app._toggleEditorFields(false);
});


$(document).ready(function() {
	$("#horizontalScroll").dataTable({ scrollX: true });
	$(".dataTables_length").addClass("bs-select");
});
