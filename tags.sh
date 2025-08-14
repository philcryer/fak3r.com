#!/bin/bash

set -e

base_path="src/content/blog"
author_name="fak3r"
old_files="../fak3r.com-main"
md_file="2018-06-10-build-a-headless-spotify-connect-server.md"

rm -rf $base_path/*
cp $md_file $base_path

find $base_path -name \*.md | while read mdfile; do

  echo; echo "+-----------------------------------------------------------------------+"
  echo "   : Rewrite filename and directory"
  file_name=$(echo $mdfile | cut -d"/" -f4)
  echo "     - filename: $file_name"
  new_dir_name=$(echo $file_name | cut -d"." -f1)
  echo "     - new directory: $new_dir_name"
  mkdir -p $base_path/$new_dir_name
  echo "     - new filename: $base_path/$new_dir_name/index.mdx"
  mv $base_path/$file_name $base_path/$new_dir_name/index.mdx

  echo; echo "+-----------------------------------------------------------------------+"
  echo "   : Frontmatter fix - tags and categories"
  echo "     - parsing current Tags and Categories lines"
  cat_array=$(cat "$base_path/$new_dir_name/index.mdx" | grep Categories)
  tag_array=$(cat "$base_path/$new_dir_name/index.mdx" | grep -i Tags)
  sm_tag_array=$(cat "$base_path/$new_dir_name/index.mdx" | grep -i tags)
  tags=$(echo ${cat_array[@]} ${tag_array[@]} ${sm_tag_array[@]} | sed 's/tags://g' | sed 's/Tags://g' | sed 's/Categories://g' | tr -d '[],' | awk '{ gsub (" ", "", $0); print}' | sed 's/""/\ /g' | sed 's/"//g')

#  echo $tags | xargs -n1 | sort -u | xargs)

#  echo $tags_uniq
#  exit 0

  tags_wquotes=$(echo $tags | tr "''" "\n" | sed '/^$/d' | sort | uniq | awk '{ printf "\"%s\",\n", $0 }')
  tags_as_string=$(echo $tags_wquotes | tr "''" "\n" | sed '/^$/d' | sort | uniq | awk -v RS= -F'\n' -v OFS=', ' '{$1=$1} 1' | sed 's/,$//')
  new_tags=$(for a in "${tags_as_string[@]}"; do echo "[ $a ]"; done)
  echo "     - new tag line: $new_tags"

  echo "     - dropping old tags, Tags, and Categories lines"
  sed -i "/tags:/d" "$base_path/$new_dir_name/index.mdx"
  sed -i "/Tags:/d" "$base_path/$new_dir_name/index.mdx"
  sed -i "/Categories:/d" "$base_path/$new_dir_name/index.mdx"

  echo "     - writing new tags string to file"
  sed -i "/date:/atags:\ $new_tags" "$base_path/$new_dir_name/index.mdx"
done
