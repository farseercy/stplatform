#include "stdio.h"
#include "stdlib.h"
int main(int argc, char *argv[])
{
    if(argc!=2){
        printf("---------------------\n");
        printf("usage: a.out &r\n");
        exit(0);
    }
    int r,s;
    r=atoi(argv[1]);
    s=r*r;
    printf("ok\n");
    printf("haha\n");
    return s;
}
