document.addEventListener("DOMContentLoaded", () => {
  const uploadButton = document.querySelector("#ct-upload-logo");
  const removeButton = document.querySelector("#ct-remove-logo");
  const logoInput = document.querySelector("#ct-logo-id");
  const logoPreview = document.querySelector("#ct-logo-preview");

  if (!uploadButton || !logoInput || !logoPreview) return;

  let mediaFrame;

  uploadButton.addEventListener("click", (event) => {
    event.preventDefault();

    if (mediaFrame) {
      mediaFrame.open();
      return;
    }

    mediaFrame = wp.media({
      title: "Select Website Logo",
      button: {
        text: "Use this logo",
      },
      library: {
        type: "image",
      },
      multiple: false,
    });

    mediaFrame.on("select", () => {
      const attachment = mediaFrame.state().get("selection").first().toJSON();

      logoInput.value = attachment.id;

      logoPreview.src = attachment.sizes?.medium?.url || attachment.url;

      logoPreview.style.display = "block";
    });

    mediaFrame.open();
  });

  removeButton?.addEventListener("click", (event) => {
    event.preventDefault();

    logoInput.value = "";
    logoPreview.removeAttribute("src");
    logoPreview.style.display = "none";
  });
});
