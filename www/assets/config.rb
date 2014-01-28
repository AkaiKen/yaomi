# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "sass"
images_dir = "img"
javascripts_dir = "js"

# To enable relative paths to assets via compass helper functions. Uncomment:
 relative_assets = true

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
if environment == :production
  output_style = :compressed
else
  output_style = :expanded
  sass_options = { :debug_info => true }
end

# Désactiver l'ajout du cache buster sur les images appelées via la fonction image-url().
asset_cache_buster :none