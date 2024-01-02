# Media Library Pro for Filament Admin

This package allows you to give your users a beautiful way to upload images to your application and manage their library. It integrates with and is based on [Spatie MediaLibrary](https://github.com/spatie/laravel-medialibrary), one of the most popular and widely used package in the Laravel ecosystem.

### Features

- Media library page in your application.
- Custom field to select a media item.
- Store a caption and alt-text on each image.
- Integration with Spatie MediaLibrary.
- Bulk-upload images.
- Responsive images.
- Ability to add items outside of Filament Admin.
- Beautiful design & integration with Filament Admin.
- Support for dark mode. 🌚
- Can be easily translated with a language file.
- Can be used outside the admin panel, with only the Form Builder.

### Screenshots

#### Library (light and dark mode)

The MediaLibrary page is the page where your users can view all their images. The complete package is compatible with both dark- and lightmode.

![Media Library page](https://ralphjsmit.com/storage/media/154/responsive-images/Schermafbeelding-2022-04-06-om-13.45.14___responsive_2720_1701.jpg)

![Media Library page Dark Mode](https://ralphjsmit.com/storage/media/155/responsive-images/FilamentMediaLibrary-Dark___responsive_2720_1701.jpg)

#### Upload & bulk upload

Users can drag-and-drop their images unto the upload component. Bulk uploads are allowed. You can use Laravel's queue processing feature to handle the process of generating responsive images in the background.

![Filament MediaLibrary regular upload](https://ralphjsmit.com/storage/media/162/responsive-images/FilamentMediaLibrary-Uploading___responsive_2032_1215.jpg)

![Media Library Bulk Upload](https://ralphjsmit.com/storage/media/156/responsive-images/FilamentMediaLibrary-BulkUpload___responsive_2032_1215.jpg)

![Filament Media Library Bulk Upload](https://ralphjsmit.com/storage/media/158/responsive-images/FilamentMediaLibrary-BulkUpload-StoringFiles___responsive_2032_1215.jpg)

#### MediaPicker Field & modal

You can use the MediaPicker Field everywhere inside the admin panel where you want it: as a single field or in a repeater. It works everywhere.

![MediaPicker Field & modal](https://ralphjsmit.com/storage/media/157/responsive-images/Custom-Field___responsive_1546_1556.jpg)

When a user clicks on 'Choose image', he/she will see the following modal, which they can use to pick an image.

![Filament MediaPicker Modal](https://ralphjsmit.com/storage/media/161/responsive-images/FilamentMediaLibrary-Modal___responsive_1690_965.jpg)

#### Image detail

For each image, you can edit the caption and the alt-text. You can view the full image URL in the browser and delete images as well.

![Filament MediaLibrary Image Detail](https://ralphjsmit.com/storage/media/159/responsive-images/FilamentMediaLibrary-Image-Detail___responsive_1917_805.jpg)

![Image detail vertical](https://ralphjsmit.com/storage/media/160/responsive-images/FilamentMediaLibrary-Image-Detail-Vertical___responsive_1390_1948.jpg)

#### Default theme

By default Filament comes with it's own CSS, which integrates neatly into the admin panel design. However, as I did in the above screenshots, it is just as beautiful if you integrate it with your own Filament theme.

![Filament MediaLibrary With Default Theme](https://ralphjsmit.com/storage/media/163/responsive-images/FilamentMediaLibrary-Page-DefaultTheme___responsive_2024_1218.jpg)

### Upgrading to V2

– The `getImage()` method has been renamed to `getImages()` and the `Illuminate\Support\Collection` return type has been added. If you are extending the MediaPicker component, please update your code accordingly.

- The translation `media.choose-image` now supports a pluralized version: `'choose image|choose images'`. If you created a custom translation, you should update the translation.

### Changelog V2

- Support selecting multiple images in the MediaPicker field.
- Fix issue with green selected border not visible in media library.
- Fix duplicate server requests in Livewire.
- Return type of the `getMedia()` function in the BrowseLibrary class updated to `Illuminate\Contracts\Pagination\Paginator`. If you extended this class and overrided this function, you should update the return type. 
