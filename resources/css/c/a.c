#include <stdio.h>
#include <string.h>
#include <ctype.h>

int main() {
    FILE *file;
    char word[100]; // 단어를 저장할 배열
    int count = 0;  // 단어 순서를 셀 변수
    int found = 0;  // 찾았는지 여부를 표시하는 변수

    // 파일 열기
    file = fopen("a.txt", "r");
    if (file == NULL) {
        printf("파일을 열 수 없습니다.\n");
        return 1;
    }

    // 파일에서 단어를 하나씩 읽음
    while (fscanf(file, "%s", word) != EOF) {
        count++; // 단어 순서 증가

        // "FREE" 단어 찾기 (대소문자 구분 없이 비교)
        if (strcasecmp(word, "FREE") == 0) {
            printf("단어 'FREE'는 %d번째 단어입니다.\n", count);
            found = 1; // 단어를 찾았음을 표시
            break;
        }
    }

    // 단어를 찾지 못한 경우 메시지 출력
    if (!found) {
        printf("파일에서 'FREE' 단어를 찾을 수 없습니다.\n");
    }

    // 파일 닫기
    fclose(file);

    return 0;
}