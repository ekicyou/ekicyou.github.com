#include "stdafx.h"
#include "checkIF.h"
#include "checkIFimpl.h"
/*
#include "import.h"
#include <atlbase.h>
#include <ShObjIdl.h>
*/


void CheckInterface(IUnknown *src, LPCTSTR name){
    AtlTrace(_T("■[%s]に存在する型一覧\n"), name);

    CheckInterface1(src);
    CheckInterface2(src);
}
