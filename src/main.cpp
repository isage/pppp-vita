#include <sapi/embed/php_embed.h>

int _newlib_heap_size_user   = 250 * 1024 * 1024;

int main(int argc, char **argv)
{
    PHP_EMBED_START_BLOCK(argc, argv)

    zend_file_handle file_handle;
    zend_stream_init_filename(&file_handle, "data/index.php");

    if (php_execute_script(&file_handle) == FAILURE) {
        php_printf("Failed to execute PHP script.\n");
    }

    PHP_EMBED_END_BLOCK()
}
