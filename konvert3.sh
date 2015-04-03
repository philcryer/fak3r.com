for file in content/post/*; do
    	#sed -i 's/author:\ phil/author:\ fak3r/' $file
	sed -i 's/assets\///g' $file
done
clear; cat $file
