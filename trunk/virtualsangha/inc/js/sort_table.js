/* ------ sorting table without page reloading ---- 

originally written by paul sowden <paul@idontsmoke.co.uk> | http://idontsmoke.co.uk
modified by alexander shurkayev <alshur@narod.ru> | http://htmlcoder.visions.ru

 ---- following variables must be defined -----

var aSortImgs = new Array(); // array containing pathes to sort indicator images
aSortImgs[0] = 'sort_up.gif';
aSortImgs[1] = 'sort_down.gif';

var sort_case_sensitive = false; // sorting type (case-sensitive or not)

//initial sorting
var initial_sort_id = 3; //number of column beginning from 0
var initial_sort_up = 1; //0 - ascendant order, 1 - descendant

*/

//attach onload event
var root = (window.addEventListener || window.attachEvent) ? window : (document.addEventListener ? document : null);
if (root)
{
    if (root.addEventListener)
		root.addEventListener("load", init, false);
    else if (root.attachEvent)
		root.attachEvent("onload", init);
}

// function defining sort algorithm
function _sort(a, b)
{
    var a = a[0];
    var b = b[0];
    var _a = (a + '').replace(/,/, '.');
    var _b = (b + '').replace(/,/, '.');
	
    if (Number(_a) && Number(_b))
		return sort_numbers(_a, _b);
    else if (!sort_case_sensitive)
		return sort_insensitive(a, b);
    else
		return sort_sensitive(a, b);
}

function sort_numbers(a, b)
{
    return a - b;
}

function sort_insensitive(a, b)
{
    var anew = a.toLowerCase();
    var bnew = b.toLowerCase();
	
    if (anew < bnew)
		return -1;
    if (anew > bnew)
		return 1;
	
    return 0;
}

function sort_sensitive(a, b)
{
    if (a < b) return -1;
    if (a > b) return 1;
    return 0;
}

function getConcatenedTextContent(node)
{
    var _result = "";
    if (node == null)
        return _result;
	
    var childrens = node.childNodes;
    var i = 0;
    while (i < childrens.length)
	{
        var child = childrens.item(i);
        switch (child.nodeType) {
            case 1: // ELEMENT_NODE
            case 5: // ENTITY_REFERENCE_NODE
                _result += getConcatenedTextContent(child);
                break;
            case 3: // TEXT_NODE
            case 2: // ATTRIBUTE_NODE
            case 4: // CDATA_SECTION_NODE
            case 8: // COMMENT_NODE
                _result += child.nodeValue;
                break;
            /*case 6: // ENTITY_NODE
            case 7: // PROCESSING_INSTRUCTION_NODE
            case 9: // DOCUMENT_NODE
            case 10: // DOCUMENT_TYPE_NODE
            case 11: // DOCUMENT_FRAGMENT_NODE
            case 12: // NOTATION_NODE
            // skip
            break;*/
        }
        i++;
    }
    return _result;
}

// core of script
function sort(e)
{
    var el = window.event ? window.event.srcElement : e.currentTarget;
    while (el.tagName.toLowerCase() != "td")
		el = el.parentNode;
	
    var a = new Array();
    var name = el.lastChild.nodeValue;
    var dad = el.parentNode;
    var table = dad.parentNode.parentNode;
    var up = table.up;
    var node, arrow, curcol;
	
    for (var i = 0; (node = dad.getElementsByTagName("td").item(i)); i++)
	{
        if (node.lastChild.nodeValue == name)
		{
            curcol = i;
            if (node.className == "curcol")
			{
                arrow = node.firstChild;
                table.up = Number(!up);
            }
			else
			{
                node.className = "curcol";
                arrow = node.insertBefore(document.createElement("img"),node.firstChild);
                table.up = 0;
            }
			
            arrow.src = aSortImgs[table.up];
            //arrow.alt = "";
        }
		else
		{
            if (node.className == "curcol")
			{
                node.className = "";
                if (node.firstChild)
					node.removeChild(node.firstChild);
            }
        }
    }
	
    var tbody = table.getElementsByTagName("tbody").item(0);
    for (var i = 0; (node = tbody.getElementsByTagName("tr").item(i)); i++)
	{
        a[i] = new Array();
        a[i][0] = getConcatenedTextContent(node.getElementsByTagName("td").item(curcol));
        a[i][1] = getConcatenedTextContent(node.getElementsByTagName("td").item(1));
        a[i][2] = getConcatenedTextContent(node.getElementsByTagName("td").item(0));
        a[i][3] = node;
    }
	
    a.sort(_sort);
    if (table.up)
		a.reverse();
	
    for (var i = 0; i < a.length; i++)
	{
        tbody.appendChild(a[i][3]);
    }
}

// whole process initialization
function init(e)
{
    if (!document.getElementsByTagName)
		return;

    for (var j = 0; (thead = document.getElementsByTagName("thead").item(j)); j++)
	{
        var node;
        for (var i = 0; (node = thead.getElementsByTagName("td").item(i)); i++)
		{
			if( node.innerHTML == '&nbsp;' ) //non-sortable columns
				continue;
			
            if (node.addEventListener)
				node.addEventListener("click", sort, false);
            else if (node.attachEvent)
				node.attachEvent("onclick", sort);
        }
		
        thead.parentNode.up = 0;
        
        if( typeof(initial_sort_id) != "undefined" )
		{
            td_for_event = thead.getElementsByTagName("td").item(initial_sort_id);
			
            if (document.createEvent) // firefox,mozilla
			{
                var evt = document.createEvent("MouseEvents");
                evt.initMouseEvent("click", false, false, window, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, td_for_event);
                td_for_event.dispatchEvent(evt);
				
				if (typeof(initial_sort_up) != "undefined" && initial_sort_up)
				{
					evt.initMouseEvent("click", false, false, window, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, td_for_event);
					td_for_event.dispatchEvent(evt);
				}
            }
			else if (td_for_event.fireEvent) // iexplorer
			{
				td_for_event.fireEvent("onclick");
				
				if (typeof(initial_sort_up) != "undefined" && initial_sort_up)
					td_for_event.fireEvent("onclick");
			}
        }
    }
}
