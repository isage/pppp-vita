<?php

declare(strict_types=1);

require 'data/bootstrap.php';

if (SDL_GetVersion($version)) {
    printf('Powered by PHP %s, SDL extension %s and SDL2 library %s.' . PHP_EOL, phpversion(), phpversion('sdl'), implode('.', $version));
} else {
    trigger_error('SDL version could not be retrieved', E_USER_NOTICE);
}

$quit = false;

SDL_Init(SDL_INIT_VIDEO | SDL_INIT_JOYSTICK);
$joystick = SDL_JoystickOpen(0);
$joystickFound = !is_null($joystick);
if (!$joystickFound) {
        trigger_error('A joystick could not be found.', E_USER_NOTICE);
}

$window = SDL_CreateWindow("example", SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED, 960, 544, SDL_WINDOW_SHOWN);
$renderer = SDL_CreateRenderer($window, -1, 0);

$texture = IMG_LoadTexture($renderer, "data/spaceship.png");
if ($texture === null) {
  exit('Unable to load image');
}

SDL_SetRenderDrawColor($renderer, 0xbb, 0xcc, 0xdd, 0xff);
SDL_RenderClear($renderer);
SDL_RenderPresent($renderer);

$rotCenter = new SDL_Point(10, 10);
$event = new SDL_Event;
$destRect = new SDL_Rect(0,0,64,64);
$destRect->x = $x = 100;
$destRect->y = $y = 100;

if (\Mix_OpenAudio(44100, MIX_DEFAULT_FORMAT, 2, 2048) < 0) {
    throw new RuntimeException("Cannot open audio device");
}

$wav = Mix_LoadWAV('data/explode.wav');

$update = true;
while (!$quit) {
        if ($joystickFound) {
                $xJoystickMotion = SDL_JoystickGetAxis($joystick, 0);
                if ($xJoystickMotion !== 0) {
                        $x += ceil($xJoystickMotion / 32767) * 5;
                        $update = true;
                }
                $yJoystickMotion = SDL_JoystickGetAxis($joystick, 1);
                if ($yJoystickMotion !== 0) {
                        $y += ceil($yJoystickMotion / 32767) * 5;
                        $update = true;
                }
        }

        while (SDL_PollEvent($event)) {
                switch ($event->type) {
                        case SDL_QUIT:
                            $quit = true;
                            break;
                        case SDL_MOUSEMOTION:
                            $x = $event->motion->x;
                            $y = $event->motion->y;
                            $update = true;
                            break;
                        case SDL_JOYAXISMOTION:
                            break;
                        case SDL_JOYBUTTONDOWN:
                            if ($event->jbutton->button == 2)
                                Mix_PlayChannel(-1, $wav, 0);
                            break;
                }
        }

        if ($update) {
                SDL_RenderClear($renderer);
                $destRect->x = intval($x);
                $destRect->y = intval($y);

                if (SDL_RenderCopyEx($renderer, $texture, NULL, $destRect, 90, $rotCenter, SDL_FLIP_NONE) != 0) {
                        echo SDL_GetError(), PHP_EOL;
                }
                SDL_RenderPresent($renderer);
                $update = false;
        }

        SDL_Delay(5);
}

if ($joystickFound) {
        SDL_JoystickClose($joystick);
}

SDL_DestroyTexture($texture);
SDL_DestroyRenderer($renderer);
SDL_DestroyWindow($window);
SDL_Quit();
