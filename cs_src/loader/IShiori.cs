﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Runtime.InteropServices;

namespace Loader
{
    [Guid("BC6068DA-EA2B-4A49-A2DE-7DE8F5355EF2")]
    [ComVisible(true)]
    public interface IShiori
    {
        bool unload();
        bool load(string loaddir);
        bool request(string req, out string res);
    }
}
