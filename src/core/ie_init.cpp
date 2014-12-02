#include "stdafx.h"
#include "iehostwindow.h"
#include "stdmethod.h"
#include "fileio.h"
#include <atlsafe.h>

/////////////////////////////////////////////////////////////////////////////
// コントロール初期化

void IEHostWindow::Init(const HINSTANCE hinst, const BSTR &loaddir, RequestQueue &qreq, ResponseQueue &qres){
    this->hinst = hinst;
    this->loaddir = loaddir;
    this->qreq = &qreq;
    this->qres = &qres;

    this->hasRegKeyWrite = HasRegKeyWrite();
    if (this->hasRegKeyWrite)InitRegKey();
    InitWindow();
    InitIE();
}

//■変更
HWND IEHostWindow::Create(
    HWND hWndParent, _U_RECT rect, LPCTSTR szWindowName,
    DWORD dwStyle, DWORD dwExStyle,
    _U_MENUorID MenuOrID, LPVOID lpCreateParam)
{
    HWND	hWnd;

    //__super::ではなく、CWindow::のCreateをCAxWindow::GetWndClassName()指定で利用
    hWnd = CWindow::Create(CAxWindow::GetWndClassName(), hWndParent, rect, szWindowName, dwStyle, dwExStyle, MenuOrID, lpCreateParam);
    if (hWnd == NULL)        return	NULL;

    refWin.SubclassWindow(m_hWnd);		//メッセージマップが使えるようにこのウインドウをサブクラス化

    //CWindow::Createで作れたコントロールをこのウインドウにアタッチ
    {
        CComPtr<IUnknown> unknown;

        AtlAxGetControl(m_hWnd, &unknown);
        if (unknown)
            AttachControl(unknown, m_hWnd);

        QueryControl(IID_IWebBrowser2, (void**)&web2);  //_pIWebBrowser2にこのビューに関連づいているIEをセットする
        if (web2 == NULL) return hWnd;                  //WebBrowser取得失敗

        //サイトも設定
        unknown = NULL;
        QueryHost(&unknown);
        SetSite(unknown);
    }

    Advise(web2);		//IEとの接続

    return	hWnd;
}

void IEHostWindow::InitWindow(){
    // window作成
    Create(NULL, CWindow::rcDefault,
        _T("IEWindow"), WS_OVERLAPPEDWINDOW | WS_VISIBLE);

    ResizeClient(320, 480);
}

//#define SHOW_PASTA_SAN

void IEHostWindow::InitIE(){
    // IEコントロールの作成
    CComPtr<IUnknown> unknown, uhost;
    HR(CreateControlEx(_T("Shell.Explorer.2"), NULL, &uhost, &unknown, IID_NULL, NULL));
    web2 = unknown;

#ifndef SHOW_PASTA_SAN
    // 空ページの作成
    CComVariant	no_use, blank_url(_T("about:blank"));
    HR(web2->Navigate2(&blank_url, &no_use, &no_use, &no_use, &no_use));

    // ドキュメントファイルの読み込み
    CComPtr<IDispatch> disp;
    CComQIPtr<IHTMLDocument2> doc2;
    CComSafeArray<VARIANT> buf;
    auto path = loaddir;
    path /= L"index.html";

    auto htmlText = readFile(path.string().c_str());
    CComBSTR bHtml(htmlText.c_str());
    CComVariant vHtml(bHtml);
    HR(buf.Add(vHtml));
    HR(web2->get_Document(&disp));
    doc2 = disp;
    HR(doc2->clear());
    HR(doc2->writeln(buf));
    {
        CComPtr<IUnknown> check = doc2;
        CComQIPtr<IViewObject> vo = check;
        CComQIPtr<IViewObjectPresentSite> ps = check;
        CComQIPtr<IViewObjectPresentNotify> nf = check;
    }
    {
        CComPtr<IUnknown> check = web2;
        CComQIPtr<IViewObject> vo = check;
        CComQIPtr<IViewObjectPresentSite> ps = check;
        CComQIPtr<IViewObjectPresentNotify> nf = check;
    }
    {
        CComQIPtr<IOleObject> ole = web2;
        CComPtr<IOleClientSite> check;
        ole->GetClientSite(&check);
        CComQIPtr<IViewObject> vo = check;
        CComQIPtr<IViewObjectPresentSite> ps = check;
        CComQIPtr<IViewObjectPresentNotify> nf = check;
    }

#else
    // ぱすたさんの読み込み
    CComVariant	no_use, blank_url(_T("http://ekicyou.github.io/pasta/app/index.html"));
    //CComVariant	no_use, blank_url(_T("http://zakkiweb.net/tools/accessinfo/"));
    HR(web2->Navigate2(&blank_url, &no_use, &no_use, &no_use, &no_use));

#endif

    {
        // どうにもならない試みであるが同じことを繰り返さないために残す
        CComQIPtr<IOleObject> ole = web2;
        CComPtr<IOleClientSite> site;
        ole->GetClientSite(&site);
        CComPtr<IViewObjectPresentSite> sv;

        CComQIPtr<IOleClientSite> site2 = uhost;
        CComQIPtr<IViewObjectPresentSite> sv2 = uhost;
    }
}