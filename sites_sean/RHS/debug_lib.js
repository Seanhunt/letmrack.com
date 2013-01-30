// ---------------------------------------------------------------------------------------------------------------------------------------
// debug_lib.js
//
// This refreshes the message window to display the messages from the run.
//
// It is called as an OnLoad event in the page being debugged by the function
// DebugMessagePump() from debug_lib.php
// 
// Revision:	080806 File Originated
//
//
// Revision 2.2.0.8
//
// © 2008 Rolling Hills Software
// Author: Ralph Cooksey-Talbott
// Contact: cooksey@rollinghillssoftware.com
// Phone: 510-742-0548
// ---------------------------------------------------------------------------------------------------------------------------------------

function DebugRefresh()
{
window.open("debug_window.php?Mode=Show#end", "_Debug_Window");
//alert("DebugRefresh() Called");
}
