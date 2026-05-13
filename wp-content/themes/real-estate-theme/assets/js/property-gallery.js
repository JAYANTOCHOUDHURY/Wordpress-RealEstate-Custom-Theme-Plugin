jQuery(document).ready(function ($) {
  const $uploadButton = $('.upload-gallery');
  const $galleryContainer = $('#gallery-preview');
  const $galleryInput = $('#property_gallery');

  if ($uploadButton.length && $galleryContainer.length && $galleryInput.length) {
    let frame;

    $uploadButton.on('click', function (e) {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: 'Select or Upload Gallery Images',
        button: {
          text: 'Use these images'
        },
        multiple: true,
        library: { type: 'image' }
      });

      frame.on('select', function () {
        const selection = frame.state().get('selection');
        const ids = [];

        $galleryContainer.empty();

        selection.each(function (attachment) {
          const data = attachment.toJSON();
          ids.push(data.id);

          // Use thumbnail size if available
          const thumb = data.sizes && data.sizes.thumbnail
            ? data.sizes.thumbnail.url
            : data.url;

          $galleryContainer.append(
            `<img src="${thumb}" style="max-width:100px;margin:5px;">`
          );
        });

        $galleryInput.val(ids.join(','));
      });

      frame.open();
    });
  }
});
