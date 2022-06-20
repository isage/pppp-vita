cmake_minimum_required(VERSION 3.0)
set(CMAKE_MODULE_PATH ${CMAKE_MODULE_PATH} "${CMAKE_SOURCE_DIR}/cmake/")

if(NOT DEFINED CMAKE_TOOLCHAIN_FILE)
  if(DEFINED ENV{VITASDK})
    set(CMAKE_TOOLCHAIN_FILE "$ENV{VITASDK}/share/vita.toolchain.cmake" CACHE PATH "toolchain file")
  else()
    message(FATAL_ERROR "Please define VITASDK to point to your SDK path!")
  endif()
endif()

include("$ENV{VITASDK}/share/vita.cmake" REQUIRED)

set(VITA_MKSFOEX_FLAGS "${VITA_MKSFOEX_FLAGS} -d PARENTAL_LEVEL=1")
set(VITA_MKSFOEX_FLAGS "${VITA_MKSFOEX_FLAGS} -d ATTRIBUTE2=12")

project(pppp-vita)

find_package(PHP)
find_package(SDL2)

include_directories(
    "src"
    ${PHP_INCLUDE_DIRS}
)

# CHANGE THOSE
set(ELFNAME "pppp")
set(TITLE_NAME "pppp-demo")
set(TITLE_ID "PPPP00817")

file(GLOB SOURCES src/*.cpp)

set(ELF "${ELFNAME}.elf")
set(SELF "${ELFNAME}.self")

add_executable("${ELF}"
  ${SOURCES}
)

target_link_libraries("${ELF}" php SDL2_mixer SDL2_image SDL2::SDL2 FLAC mpg123 opusfile opus modplug mikmod vorbisfile vorbis ogg png jpeg webp pthread z c m)

VITA_create_self("${SELF}"
  "${ELF}"
  UNSAFE
)

# Build VPK

set(VPK "${ELFNAME}.vpk")
set(TITLE_VER "01.00")

VITA_create_vpk("${VPK}" "${TITLE_ID}" "${SELF}"
  NAME "${TITLE_NAME}"
  VERSION "${TITLE_VER}"
  FILE sce_sys sce_sys
  FILE data data
)
