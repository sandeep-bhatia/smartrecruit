/*****************************************************************************************************************************************************
Copyright © Think Triangle Oyc
Author : Sandeep Bhatia
Reviewers : (None)
Last Update Description : Initial File Creation done.
*****************************************************************************************************************************************************/
package  {
	
	public class Queue {
		private var first:QueueNode;
		private var last:QueueNode;
		public function isEmpty():Boolean{
				return (first == null);
		}

		public function push(data:Object):void{
				var node:QueueNode = new QueueNode();
				node.data = data;
				node.next = null;
				if (isEmpty()) {
									first = node;
									last = node;
				} else {
									last.next = node;
									last = node;
				}

		}
 
		public function pop():Object{
				 if (isEmpty()) {
				 return null; //if stack has no data inside that, you can handle it here
			}
 
			var data = first.data;
 			first = first.next;
 			return data;
 
		}
 
	}
	
}
