#!/bin/bash

declare -A MODULES
declare -A THEMES

MODULES["Navigation"]="https://github.com/Mewgan/navigation-module.git"
MODULES["Post"]="https://github.com/Mewgan/post-module.git"
MODULES["GridEditor"]="https://github.com/Mewgan/grid-editor-module.git"
MODULES["Team"]="https://github.com/Mewgan/team-module.git"
MODULES["Price"]="https://github.com/Mewgan/price-module.git"
MODULES["Payment"]="https://github.com/Mewgan/payment-module.git"
# Partners modules
MODULES["Ikosoft"]="https://github.com/Mewgan/ikosoft-module.git"
MODULES["Ikinoa"]="https://github.com/Mewgan/ikinoa-module.git"

# Salon themes
THEMES["Aster"]="https://github.com/Mewgan/aster-theme.git"
THEMES["Balsamine"]="https://github.com/Mewgan/balsamine-theme.git"
THEMES["Heliotrope"]="https://github.com/Mewgan/heliotrope-theme.git"
# Sport themes
THEMES["Marathon"]="https://github.com/Mewgan/marathon-theme.git"
THEMES["Triathlon"]="https://github.com/Mewgan/triathlon-theme.git"

STORAGE_FOLDERS=(cache template sessions)

if [ ! -d 'storage' ]
then
    mkdir 'storage'
fi

cd 'storage/'
for folder in ${STORAGE_FOLDERS[*]}
do
    if [ ! -d $folder ]
    then
        mkdir $folder
        chmod 777 -R $folder
    fi
done

cd '../src/'
if [ ! -d 'Modules' ]
then
    mkdir 'Modules'
fi

cd 'Modules/'
for module in ${!MODULES[@]}
  do
    if [ -d $module ]
    then
        cd ${module}
        git pull origin master
        cd '../'
    else
        git clone ${MODULES[${module}]} ${module}
    fi
done

cd '../'
if [ ! -d 'Themes' ]
then
    mkdir 'Themes'
fi

cd 'Themes/'
for theme in ${!THEMES[@]}
  do
    if [ -d $theme ]
    then
        cd ${theme}
        git pull origin master
        cd '../'
    else
        git clone ${THEMES[${theme}]} ${theme}
    fi
done


